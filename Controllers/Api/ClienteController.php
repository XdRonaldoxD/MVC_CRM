<?php

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_GET['consultaquery'])) {
    require_once "models/Cliente.php";
    require_once "models/Usuario.php";
    require_once "models/Departamento.php";
    require_once "models/Provincia.php";
    require_once "models/Perfil.php";
    require_once "models/Pedido.php";
    require_once "models/PedidoDetalle.php";
    require_once "models/Folio.php";
} else {
    require_once "models/ConsultaGlobal.php";
}
class ClienteController
{

    public function TraerDepartamentoCliente()
    {
        $Departamento = Departamento::where("idPais", 1)->get();
        echo $Departamento;
    }

    public function TraerProvinciaCliente()
    {
        $Departamento = Provincia::where("idDepartamento", $_GET['id_departamento'])->get();
        echo $Departamento;
    }

    public function ValidarDNICliente()
    {
        $Departamento = Provincia::where("idDepartamento", $_GET['id_departamento'])->get();
        echo $Departamento;
    }

    public function ActualizarPasswordCliente()
    {
        $contrasenia = hash('sha256', $_POST['password_anterior']);
        $contrasenia_actual = hash('sha256', $_POST['password_actual']);
        $cliente =  Cliente::join('usuario', "usuario.id_cliente", "cliente.id_cliente")
            ->where('cliente.id_cliente',  $_POST['id_cliente'])
            ->where('usuario.password_usuario', $contrasenia)
            ->first();
        if (isset($cliente)) {
            $usuario = [
                "password_usuario" => $contrasenia_actual
            ];
            Usuario::where("id_cliente", $_POST['id_cliente'])->update($usuario);
            echo json_encode("actualizado");
        } else {
            echo "Contraseña no válida";
            http_response_code(400);
        }
    }
    public function GuardarCliente()
    {
        $formulario = json_decode($_POST['formulario']);
        $existeCliente =  Cliente::join('usuario', "usuario.id_cliente", "cliente.id_cliente")
            ->where('dni_cliente', $formulario->dni_cliente)
            ->where('dv_cliente', $formulario->dv_cliente)
            ->first();
        $apellidos_cliente = explode(" ", $formulario->apellidos_cliente);
        $datos = array(
            "dni_cliente" => $formulario->dni_cliente,
            "dv_cliente" => $formulario->dv_cliente,
            "nombre_cliente" => $formulario->nombre_cliente,
            "apellidopaterno_cliente" => $apellidos_cliente[0],
            "apellidomaterno_cliente" => isset($apellidos_cliente[1]) ? $apellidos_cliente[1] : null,
            "e_mail_cliente" => $formulario->e_mail_cliente,
            "celular_cliente" => $formulario->celular_cliente,
            'direccion_cliente' => $formulario->direccion_cliente,
            'telefono_cliente' => $formulario->telefono_cliente,
            "vigente_cliente" => 1,
            "tipodocumento_cliente" => "DNI",
            'fechacreacion_cliente' => date('Y-m-d H:i:s'),
            'mediollegada_cliente' => "RESERVA_ONLINE",
            "idProvincia" => $formulario->idProvincia
        );
        if (empty($existeCliente)) {
            $nuevoCiente = Cliente::create($datos);
            if ($formulario->crearcuenta == true) {
                $contrasenia = hash('sha256', $formulario->password);
                $dataPerfilCliente = Perfil::where('perfildefecto_cliente', 1)->first();
                $datoNuevoUsuario = [
                    'id_tipo_usuario' => 7,
                    'id_cliente' => $nuevoCiente['id_cliente'],
                    'password_usuario' => $contrasenia,
                    'fechacreacion_usuario' => date('Y-m-d H:i:s'),
                    'vigente_usuario' => 1,
                    'id_perfil' => $dataPerfilCliente['id_perfil'],
                ];
                $usuario = Usuario::create($datoNuevoUsuario);
                $datos += [
                    'id_usuario' =>  $usuario->id_usuario,
                ];
            } else {
                $datos += [
                    'id_usuario' =>  null,
                ];
            }
            $datos += [
                'id_cliente' => $nuevoCiente['id_cliente'],
                "idDepartamento" => $formulario->idDepartamento
            ];
            $rpta = [
                'dni_cliente' => $formulario->dni_cliente . '-' . $formulario->dv_cliente,
                'success' => true,
            ];
        } else {
            Cliente::where("id_cliente", $formulario->id_cliente)->update($datos);
            $datos += [
                'id_cliente' => $formulario->id_cliente,
                "idDepartamento" => $formulario->idDepartamento,
                'id_usuario' => $formulario->id_usuario,
            ];
            $rpta = [
                'success' => true,
            ];
        }
        $sitio_cliente = Provincia::join('departamentos', 'departamentos.idDepartamento', "provincia.idDepartamento")
            ->where("provincia.idProvincia", $formulario->idProvincia)
            ->first();
        $datos += [
            "provincia" => $sitio_cliente->provincia,
            "departamento" => $sitio_cliente->departamento,
        ];
        $rpta += [
            "datos" => $datos,
        ];
        if (isset($_POST['crear_pedido'])) {
            return $rpta;
        } else {
            echo json_encode($rpta);
        }
    }



    public function CambiarContraseniaUsuario()
    {
        $data_usuario = Usuario::where('usuario.id_usuario', $_POST['id_usuario'])
            ->first();
        $contrasenia_anterior = hash('sha256', $_POST['contrasenia_anterior']);
        if (password_verify($data_usuario->password_usuario, $contrasenia_anterior)) {
            $contrasenia = hash('sha256', $_POST['contrasenia_actual']);
            $data_usuario->password_usuario = $contrasenia;
            $data_usuario->save();
            echo json_encode("Contraseña actualizado");
            http_response_code(200);
        } else {
            echo json_encode("Contraseña no válida");
            http_response_code(400);
        }
        return json_encode("exito");
    }

    public function LoginCliente()
    {

        $pws = hash('sha256', $_POST['password_usuario']);
        $cliente = Cliente::join("usuario", "usuario.id_cliente", "cliente.id_cliente")
            ->join("provincia", "provincia.idProvincia", "cliente.idProvincia")
            ->where("cliente.e_mail_cliente", $_POST['e_mail_cliente'])
            ->where("usuario.password_usuario", $pws)
            ->select("cliente.*", 'provincia.idDepartamento', "usuario.id_usuario")
            ->first();
        if (isset($cliente)) {
            echo json_encode($cliente);
        } else {
            echo json_encode("Cliente no existe");
            http_response_code(400);
        }
    }
    public function listarPedidosCliente()
    {

        $ConsultaApi = new ConsultaGlobal();
        $ListarPedidoApi = $ConsultaApi->ListarPedidoApi($_GET['id_cliente']);
        echo json_encode($ListarPedidoApi);
    }

    public function TraerPedidoDetalle()
    {
        $PedidoDetalle = PedidoDetalle::join('producto', 'producto.id_producto', "pedido_detalle.id_producto")
            ->join('pedido', "pedido.id_pedido", "pedido_detalle.id_pedido")
            ->join('estado_pedido', 'estado_pedido.id_estado_pedido', 'pedido.id_estado_pedido')
            ->join('cliente', "cliente.id_cliente", "pedido.id_cliente")
            ->join("provincia", "provincia.idProvincia", "cliente.idProvincia")
            ->join("departamentos", "provincia.idDepartamento", "departamentos.idDepartamento")
            ->leftjoin('producto_imagen', "producto_imagen.id_producto", 'producto.id_producto')
            ->where("producto_imagen.portada_producto_imagen", 1)
            ->where('pedido.id_pedido', $_GET['id_pedido'])
            // ->select("producto.*","pedido_detalle.valorneto_pedido_detalle","pedido_detalle.cantidad_pedido_detalle","pedido_detalle.fechacreacion_pedido_detalle",
            // "cliente.*","provincia.provincia","departamentos.departamento","pedido.*",'producto_imagen.path_producto_imagen')
            ->get()
            ->toArray();
        $item = [];
        // print_r($PedidoDetalle[0]);
        // die;
        foreach ($PedidoDetalle as $key => $value) {
            $element = [
                'id' => $value['id_producto'],
                'slug' => $value['urlamigable_producto'],
                'name' =>  $value['glosa_producto'],
                'image' => $value['path_producto_imagen'],
                'price' => $value['precioventa_producto'],
                'quantity' => $value['cantidad_pedido_detalle'],
                'total' => $value['valorneto_pedido_detalle']
            ];
            array_push($item, $element);
        }
        $pedido = [
            'id' => $_GET['id_pedido'],
            'date' => date('d/m/Y', strtotime($PedidoDetalle[0]['fechacreacion_pedido_detalle'])),
            'status' => $PedidoDetalle[0]['glosa_estado_pedido'],
            'items' => $item,
            'additionalLines' => [],
            'quantity' => null,
            'subtotal' => $PedidoDetalle[0]['valorneto_pedido'],
            'total' => $PedidoDetalle[0]['valortotal_pedido'],
            'igv' => $PedidoDetalle[0]['porcentajeiva_pedido'],
            'paymentMethod' => '',
            'shippingAddress' => [
                'firstName' =>  $PedidoDetalle[0]['nombre_cliente'],
                'lastName' => $PedidoDetalle[0]['apellidopaterno_cliente'] . ' ' . $PedidoDetalle[0]['apellidomaterno_cliente'],
                'email' =>  $PedidoDetalle[0]['e_mail_cliente'],
                'phone' => $PedidoDetalle[0]['celular_cliente'],
                'country' => 'PERU',
                'city' =>  $PedidoDetalle[0]['provincia'],
                'postcode' => $PedidoDetalle[0]['departamento'],
                'address' => $PedidoDetalle[0]['direccion_cliente'],
            ],
            // 'billingAddress' => [
            //     'firstName' => 'Jupiter',
            //     'lastName' => 'Saturnov',
            //     'email' => 'stroyka@example.com',
            //     'phone' => 'ZX 971 972-57-26',
            //     'country' => 'RandomLand',
            //     'city' => 'MarsGrad',
            //     'postcode' => '4b4f53',
            //     'address' => 'Sun Orbit, 43.3241-85.239'
            // ],
        ];


        echo json_encode($pedido);
    }

    public function EnviarDatosPedidosCliente()
    {

        if ($_POST['tipo_pago'] == "2") {
            // var_dump ($_FILES['Imagen']);
            //crear el directorio
            if (!file_exists(__DIR__ . "/../../archivo/imagen_pedido")) {
                mkdir(__DIR__ . "/../../archivo/imagen_pedido", 0777, true);
            }
            $fechacreacion = date('Y-m-d');
            $Fecha = explode("-", $fechacreacion);
            $nombre = mt_srand(10) . "_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . '' . $_FILES['Imagen']['name'];
            $guardado = $_FILES['Imagen']['tmp_name'];
            move_uploaded_file($guardado, __DIR__ . "/../../archivo/imagen_pedido/$nombre");
            //PAGINA WEN (VERIFICAR IMAGEN)
            // https://sightengine.com/docs/getstarted
            $params = array(
                'media' => new CurlFile(__DIR__ . "/../../archivo/imagen_pedido/$nombre"),
                // 'url' =>  'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRMvVEnlUtT6iEz3iHVfPvCgFerKEzxRmeMug&usqp=CAU',
                'models' => 'nudity,wad,gore',
                'api_user' => '1591486713',
                'api_secret' => 'EQbtn8gLGWb37QUSAkwz',
            );

            // this example uses cURL
            $ch = curl_init('https://api.sightengine.com/1.0/check.json');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $response = curl_exec($ch);
            curl_close($ch);
            $output = json_decode($response, true);
            if ($output['alcohol'] > 0.20 || $output['nudity']['partial'] > 0.20) {
                unlink(__DIR__ . "/../../archivo/imagen_pedido/$nombre");
                echo json_encode("Error");
                http_response_code(403);
                die;
            }
            $id_estado_pago = 5;
        } else {
            $id_estado_pago = 1;
        }

        $_POST['crear_pedido'] = true;
        $respuesta_cliente = $this->GuardarCliente();
        $totales_productos = json_decode($_POST['totales_productos']);
        $Folio = Folio::where("id_folio", 11)->first();
        $datos_pedido = [
            'id_usuario' => $respuesta_cliente['datos']['id_usuario'],
            'id_folio' => $Folio->id_folio,
            'id_cliente' => $respuesta_cliente['datos']['id_cliente'],
            'id_estado_pedido' => 1,
            'id_estado_pago' => $id_estado_pago,
            'id_estado_preparacion' => 5,
            'idProvincia' => $respuesta_cliente['datos']['idProvincia'],
            'fechacreacion_pedido' => date('Y-m-d H:i:s'),
            'numero_pedido' => $Folio->numero_folio,
            'valorneto_pedido' => $totales_productos->subtotal,
            // 'valortransporte_pedido' => null,
            // 'descuento_pedido' => null,
            // 'porcentajeiva_pedido' => null,
            'iva_pedido' => $totales_productos->igv,
            'valortotal_pedido' => $totales_productos->total,
            // 'retiroentienda_pedido' => null,
            // 'peso_pedido' => null,
            // 'nota_pedido' => null,
            'vigente_pedido' => 1
        ];
        $Pedido = Pedido::create($datos_pedido);
        $Folio->numero_folio += 1;
        $Folio->save();
        $Productos = json_decode($_POST['productos']);
        $datos = [];
        foreach ($Productos as $key => $elemento) {
            $iva_pedido_detalle = $elemento->product->price / 1.18;
            $iva_pedido_detalle = $elemento->product->price - $iva_pedido_detalle;
            $datos = [
                'id_pedido' => $Pedido->id_pedido,
                'id_producto' => $elemento->product->id,
                'iva_pedido_detalle' => $iva_pedido_detalle,
                'valortotal_pedido_detalle' => $elemento->product->price,
                'cantidad_pedido_detalle' => $elemento->product->stock,
                'fechacreacion_pedido_detalle' => date('Y-m-d H:i:s'),
                'orden_pedido_detalle' => $key + 1,
            ];
            PedidoDetalle::create($datos);
        }

        ob_start();
        require_once 'generar-pdf/pdf/pedido_detalle.php';
        $html = ob_get_clean();
        ////
        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = 0;                       // Enable verbose debug output
            $mail->isSMTP();
            // smtp.mandrillapp.com  
            // smithxd118@gmail.com     
            // a74dac0e781527e2e06bd66041783587-us14
            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                  // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'smithxd118@gmail.com';                     // SMTP username
            $mail->Password   = 'txbctuntlnkrhwnr';                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            // id=dbecf254af
            //Recipients
            //DESDE DONDE
            $mail->setFrom('smithxd118@gmail.com', 'Ronaldo');
            //PARA QUIEN
            $mail->addAddress('smithxd108@gmail.com');
            // $mail->addAddress('rdurand@wilsoft.cl');  
            //copia
            $mail->addCC('smithxd118@gmail.com');
            // $mail->addAttachment($output);    // Add a recipient
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Asunto muy Importante';
            $mail->Body    = $html;
            $mail->CharSet = 'UTF-8';
            $mail->send();
            $respuesta = 'ok';
        } catch (Exception $e) {
            echo "Ubo un error al Enviar {$e->getMessage()})";
            http_response_code(403);
            die;
        }
        $respuestaDetalla = [
            "datos_pedido" => $datos_pedido,
            "datos_detalle_pedido" => $Productos,
            "cliente" => $respuesta_cliente['datos'],
            "respuesta" => "ok"
        ];
        echo json_encode($respuestaDetalla);
    }

   
}
