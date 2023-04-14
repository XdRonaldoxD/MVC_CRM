<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once "models/Producto.php";
require_once "models/ProductoColor.php";
require_once "models/ProductoImagen.php";
require_once "models/ProductoRelacionado.php";
require_once "models/ProductoEspecificaciones.php";
require_once "models/AtributoProducto.php";
require_once "models/CategoriaProducto.php";
require_once "models/MediosPago.php";
require_once "models/Departamento.php";
require_once "models/Provincia.php";
require_once "models/Distrito.php";
require_once "models/Cliente.php";
require_once "models/Usuario.php";
require_once "models/Caja.php";
require_once "models/ConsultaGlobal.php";
require_once "config/Parametros.php";


class NotaVentaController
{
    public function ListaProductos()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        if (isset($DatosPost->filtro_buscar)) {
            $buscar = $DatosPost->filtro_buscar;
        } else {
            $buscar = '';
        }

        $consulta = " and (p.codigo_barra_producto = '$buscar' or p.codigooriginal_producto LIKE '%$buscar%' or p.codigo_producto LIKE '%$buscar%' or p.glosa_producto LIKE '%$buscar%'
            or p.precioventa_producto LIKE '%$buscar%' or ti.glosa_tipo_inventario LIKE '%$buscar%') ";
        $query = "SELECT * FROM producto as p 
        INNER JOIN tipo_producto as tp on tp.id_tipo_producto = p.id_tipo_producto 
        LEFT JOIN tipo_inventario as ti on p.id_tipo_inventario=ti.id_tipo_inventario
        WHERE  p.vigente_producto=1 
        and p.stock_producto>0
        $consulta ";
        $ConsultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $DatosPost->start ";
        $ConsultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => count($ConsultaGlobalLimit),
            "recordsFiltered" => count($ConsultaGlobalLimit),
            "data" => $ConsultaGlobal
        );
        echo json_encode($datos);
    }

    public function ListaMediosPagos()
    {
        $MediosPago = MediosPago::where('vigente_medio_pago', 1)->get();
        echo $MediosPago;
    }

    public function TraerDepartamento()
    {
        $Departamento = Departamento::where('idPais', 1)->get();
        echo $Departamento;
    }

    public function TraerProvincia()
    {
        $Provincia = Provincia::where('idDepartamento', $_GET['id_departamento'])->get();
        echo $Provincia;
    }

    public function TraerDistrito()
    {
        $Distrito = Distrito::where('idProvincia', $_GET['id_provincia'])->get();
        echo $Distrito;
    }

    public function GuardarCliente()
    {
        $informacion_cliente = json_decode($_POST['informacion_cliente']);
        if ($informacion_cliente->tipoDocumento == "RUC") {
            $ruc_existe = Cliente::where('tipodocumento_cliente', $informacion_cliente->tipoDocumento)
                ->where('ruc_cliente', $informacion_cliente->ruc_cliente)
                ->first();
            if (isset($ruc_existe)) {
                http_response_code(404);
                die('Existe ruc');
            }
            $datos = [
                'ruc_cliente' => $informacion_cliente->ruc_cliente,
            ];
        } else {
            $dni_existe = Cliente::where('tipodocumento_cliente', $informacion_cliente->tipoDocumento)
                ->where('dni_cliente', $informacion_cliente->dni_cliente)
                ->first();
            if (isset($dni_existe)) {
                echo json_encode("Existe Dni");
                die(http_response_code(404));
            }
            $datos = [
                'dni_cliente' => $informacion_cliente->dni_cliente,
            ];
        }
        $datos += [
            'estado' => $informacion_cliente->estado,
            'idProvincia' => !empty($informacion_cliente->provincia) ?  $informacion_cliente->provincia : null,
            'tipodocumento_cliente' => $informacion_cliente->tipoDocumento,
            'nombre_cliente' => $informacion_cliente->nombre_razon_social,
            'apellidopaterno_cliente' => $informacion_cliente->apellido_paterno,
            'apellidomaterno_cliente' => $informacion_cliente->apellido_materno,
            'e_mail_cliente' => $informacion_cliente->e_mail_cliente,
            'celular_cliente' => $informacion_cliente->celular_cliente,
            'direccion_cliente' => $informacion_cliente->direccion_cliente,
            'fechacreacion_cliente' => date('Y-m-d H:i:s'),
            'vigente_cliente' => 1,
            'dv_cliente' => $informacion_cliente->dv_cliente
        ];

        $cliente = Cliente::create($datos);
        echo $cliente;
    }

    public function VerificarCajaAbierta()
    {
        $usuario = Usuario::join('staff', 'staff.id_staff', 'usuario.id_staff')
            ->where('usuario.id_usuario', $_GET['id_usuario'])->first();
        $caja_existe = Caja::where("id_staff", $usuario->id_staff)->where('estado_caja', 1)->first();
        if (isset($caja_existe)) {
            echo json_encode($caja_existe->id_caja);
        } else {
            echo json_encode(false);
        }
    }

    public function AsignarClienteGenerico()
    {
        $cliente = Cliente::where('dni_cliente', '00000000')->first();
        if (isset($cliente)) {
            echo json_encode($cliente);
        } else {
            http_response_code(404);
        }
    }

    public function BuscarDepartamento()
    {
        $departamento = Departamento::where("departamento", $_GET['departamento'])->first();
        if (isset($departamento)) {
            echo json_encode($departamento->idDepartamento);
        } else {
            echo json_encode("No existe departamento en base");
            http_response_code(404);
        }
    }
    public function BuscarProvincia()
    {
        $Provincia = Provincia::where("provincia", $_GET['provincia'])->first();
        if (isset($Provincia)) {
            echo json_encode($Provincia->idProvincia);
        } else {
            echo json_encode("No existe departamento en base");
            http_response_code(404);
        }
    }
    public function BuscarDistrito()
    {
        $Distrito = Distrito::where("distrito", $_GET['distrito'])->first();
        if (isset($Distrito)) {
            echo json_encode($Distrito->idDistrito);
        } else {
            echo json_encode("No existe departamento en base");
            http_response_code(404);
        }
    }

    public function EnviarCorreloElectronicoEmail()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);


        switch ($DatosPost->tipo_documento) {
            case 'BOLETA':
                $html = '<h1>Boleta enviada con éxito</h1>';
                $setFrom = 'BOLETA';
                break;
            case 'FACTURA':
                $html = '<h1>Factura enviada con éxito</h1>';
                $setFrom = 'FACTURA';
                break;
            default:
                $setFrom = 'NOTA VENTA';
                $html = '<h1>Nota Venta enviada con éxito</h1>';
                break;
        }

        if ($DatosPost->formato === "TICKET") {
            $setFrom = 'TICKET';
            $correo = $DatosPost->Correo_ticket;
            $url=$DatosPost->url_ticket;
        } else {
            $correo = $DatosPost->Correo_pdf;
            $url = $DatosPost->url_pdf;
        }

        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = 0;                       // Enable verbose debug output
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
            $mail->isSMTP();
            // smtp.mandrillapp.com  
            // smithxd118@gmail.com     
            // a74dac0e781527e2e06bd66041783587-us14
            // Send using SMTP
            $mail->Host       = Host;                  // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = Username;                     // SMTP username
            $mail->Password   = Password;                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = Port;
            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            // id=dbecf254af
            //Recipients
            //DESDE DONDE

            $mail->setFrom(Username, " $setFrom ELECTRONICA ");
            //PARA QUIEN
            // $mail->addAddress('smithxd108@gmail.com');
            $mail->addAddress($correo);
            //copia
            // $mail->addCC('smithxd118@gmail.com');
            // $mail->addAttachment($output);    // Add a recipient
            //AGREGRAMOS EL ARCHIVO URL
            $fichero = file_get_contents($url);
            $mail->addStringAttachment($fichero, "$setFrom.pdf");
            //
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $setFrom;
            $mail->Body    = $html;
            $mail->CharSet = 'UTF-8';
            $mail->send();
            $respuesta = 'ok';
        } catch (Exception $e) {
            $respuesta = $e->getMessage();
            echo "Ubo un error al Enviar {$e->getMessage()})";
            http_response_code(403);
            die;
        }

        echo json_encode($respuesta);
    }
}
