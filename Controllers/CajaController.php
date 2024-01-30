<?php

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;

require_once "models/ConsultaGlobal.php";
require_once "models/Caja.php";
require_once "models/Usuario.php";
require_once "models/EmpresaVentaOnline.php";
require_once "config/Parametros.php";

class CajaController
{

    public function ListaCaja_Habilitado_Deshabilitado()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;
        $fechas = '';
        if ($DatosPost->fechacreacion_caja_inicio) {
            $fechas .= ' and ';
            $fechas .= " caja.fechacreacion_caja>='$DatosPost->fechacreacion_caja_inicio 00:00:00' ";
        }
        if ($DatosPost->fechacreacion_caja_fin) {
            $fechas .= ' and ';
            $fechas .= " caja.fechacreacion_caja<='$DatosPost->fechacreacion_caja_fin 23:59:59' ";
        }

        $consulta = " and (concat(staff.nombre_staff,' ',staff.apellidopaterno_staff,' ',staff.apellidomaterno_staff) like '%$buscar%' or staff.nombre_staff LIKE  '%$buscar%' or staff.apellidopaterno_staff LIKE '%$buscar%' or staff.apellidomaterno_staff LIKE '%$buscar%') ";
        $query = "SELECT * FROM caja  
        inner join staff using (id_staff)
        WHERE  caja.estado_caja=$DatosPost->estado  $consulta $fechas
        order by caja.fechacreacion_caja desc ";
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

    public function GuardarCaja()
    {
        $usuario = Usuario::join('staff', 'staff.id_staff', 'usuario.id_staff')
            ->where('usuario.id_usuario', $_POST['id_usuario'])->first();
        $datos = [
            'id_staff' => $usuario->id_staff,
            'fechacreacion_caja' => date('Y-m-d H:i:s'),
            'estado_caja' => 1,
            'montoinicial_caja' => $_POST['montoinicial_caja']
        ];

        Caja::create($datos);
        echo json_encode("Creado con exito");
    }

    public function InformacionCaja($id_caja)
    {
        $query_ingreso = "SELECT * from ingreso 
        INNER JOIN negocio USING (id_negocio)
        INNER JOIN medio_pago USING (id_medio_pago)
        inner join tipo_ingreso USING (id_tipo_ingreso)
        LEFT JOIN boleta on boleta.id_negocio=negocio.id_negocio
        LEFT JOIN factura on factura.id_negocio=negocio.id_negocio
        LEFT JOIN nota_venta on nota_venta.id_negocio=negocio.id_negocio
        where id_caja=$id_caja ";

        $query_egreso = "SELECT * from egreso 
        INNER JOIN negocio USING (id_negocio)
        inner join tipo_egreso USING (id_tipo_egreso)
        LEFT JOIN boleta on boleta.id_negocio=negocio.id_negocio
        LEFT JOIN factura on factura.id_negocio=negocio.id_negocio
        LEFT JOIN nota_venta on nota_venta.id_negocio=negocio.id_negocio
        where id_caja=$id_caja ";

        $Ingreso = (new ConsultaGlobal())->ConsultaGlobal($query_ingreso);
        $Egreso = (new ConsultaGlobal())->ConsultaGlobal($query_egreso);

        $total_efectivo = 0;
        $total_caja = 0;
        $data_efectivo = [];
        $data_no_efectivo = [];

        foreach ($Ingreso as $key => $value) {
            $total_caja += $value->valor_ingreso;
            if ($value->afectacaja_tipo_ingreso == 1) {
                $total_efectivo += $value->valor_ingreso;
                $exite = false;
                foreach ($data_efectivo as $i => $element) {
                    if ($element['id_medio_pago'] === $value->id_medio_pago) {
                        $data_efectivo[$i]['cantidad'] += 1;
                        $data_efectivo[$i]['valor_pago'] += intval($value->valor_ingreso);
                        $exite = true;
                        break;
                    }
                }
                if (!$exite) {
                    $efectivos = [
                        'id_medio_pago' => $value->id_medio_pago,
                        "glosa_pago" => $value->glosa_medio_pago,
                        "valor_pago" => $value->valor_ingreso,
                        "documento" => "INGRESO",
                        "cantidad" => 1
                    ];
                    array_push($data_efectivo, $efectivos);
                }
            } else {
                $exite = false;
                foreach ($data_no_efectivo as $key => $element) {
                    if ($element['id_medio_pago'] === $value->id_medio_pago) {
                        $data_no_efectivo[$key]['cantidad'] += 1;
                        $data_no_efectivo[$key]['valor_pago'] += $value->valor_ingreso;
                        $exite = true;
                        break;
                    }
                }
                if (!$exite) {
                    $efectivos = [
                        'id_medio_pago' => $value->id_medio_pago,
                        "glosa_pago" => $value->glosa_medio_pago,
                        "valor_pago" => $value->valor_ingreso,
                        "documento" => "INGRESO",
                        "cantidad" => 1
                    ];
                    array_push($data_no_efectivo, $efectivos);
                }
            }
        }

        foreach ($Egreso as $key => $value) {
            $total_caja -= $value->valor_egreso;
            if ($value->afectacaja_tipo_egreso == 1) {
                $total_efectivo -= $value->valor_egreso;
                $exite = false;
                foreach ($data_efectivo as $j => $element) {
                    if ($element['id_medio_pago'] === $value->id_tipo_egreso) {
                        $data_efectivo[$j]['cantidad'] += 1;
                        $data_efectivo[$j]['valor_pago'] += $value->valor_egreso;
                        $exite = true;
                        break;
                    }
                }
                if (!$exite) {
                    $efectivos = [
                        'id_medio_pago' => $value->id_tipo_egreso,
                        "glosa_pago" => $value->glosa_tipo_egreso,
                        "valor_pago" => $value->valor_egreso,
                        "documento" => "EGRESO",
                        "cantidad" => 1
                    ];
                    array_push($data_efectivo, $efectivos);
                }
            } else {
                $exite = false;
                foreach ($data_no_efectivo as $z => $element) {
                    if ($element['id_medio_pago'] === $value->id_tipo_egreso) {
                        $data_no_efectivo[$z]['cantidad']++;
                        $data_no_efectivo[$z]['valor_pago'] += $value->valor_egreso;
                        $exite = true;
                        break;
                    }
                }
                if (!$exite) {
                    $efectivos = [
                        'id_medio_pago' => $value->id_tipo_egreso,
                        "glosa_pago" => $value->glosa_tipo_egreso,
                        "valor_pago" => $value->valor_egreso,
                        "documento" => "EGRESO",
                        "cantidad" => 1
                    ];
                    array_push($data_no_efectivo, $efectivos);
                }
            }
        }
        $caja = Caja::where('id_caja', $id_caja)
            ->leftjoin('staff', 'staff.id_staff', 'caja.id_caja')
            ->first();
        $respuesta = [
            "total_efectivo" => $total_efectivo + $caja->montoinicial_caja,
            "total_caja" => $total_caja + $caja->montoinicial_caja,
            "data_no_efectivo" => $data_no_efectivo,
            "data_efectivo" => $data_efectivo,
            'montoinicial_caja' => $caja->montoinicial_caja
        ];
        return $respuesta;
    }

    public function TraerDetalleCaja()
    {
        if (isset($_GET['id_caja'])) {
            $id_caja = $_GET['id_caja'];
        } else {
            $id_caja = $_POST['id_caja'];
        }
        $respuesta = $this->InformacionCaja($id_caja);
        $caja = Caja::where('id_caja', $id_caja)
            ->leftjoin('staff', 'staff.id_staff', 'caja.id_staff')
            ->first();
        if (isset($_GET['id_caja'])) {
            $empresaVentaOnline = EmpresaVentaOnline::where('id_empresa_venta_online', $_GET['id_empresa'])->first();
            $imagen = base64_encode(file_get_contents($empresaVentaOnline->urllogovertical_empresa_venta_online));
            ob_start();
            if ($_GET['Formato'] == "TICKET") {
                require_once 'generar-pdf/pdf/TicketCaja.php';
                $setpaper = array(0, 0, 221, 544);
            } else {
                require_once 'generar-pdf/pdf/ResumenCaja.php';
                $setpaper = "A4";
            }
            $html = ob_get_clean();
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper($setpaper);
            // Render the HTML as PDF
            $dompdf->render();
            $fecha = date("Y_m_d");
            // Output the generated PDF to Browser
            $dompdf->stream("Cierre_Caja_$fecha.pdf", array("Attachment" => 0));
        } else {
            echo json_encode($respuesta);
        }
    }




    public function MostrarDocumentos()
    {
        if ($_GET['tipo_documento'] == "INGRESO") {
            $query_ingreso = "SELECT *,'" . RUTA_ARCHIVO . "/archivo/".DOMINIO_ARCHIVO."' as ruta_archivo from ingreso
            INNER JOIN negocio USING (id_negocio)
            INNER JOIN medio_pago USING (id_medio_pago)
            inner join tipo_ingreso USING (id_tipo_ingreso)
            LEFT JOIN boleta on boleta.id_negocio=negocio.id_negocio
            LEFT JOIN factura on factura.id_negocio=negocio.id_negocio
            LEFT JOIN nota_venta on nota_venta.id_negocio=negocio.id_negocio
            where id_caja={$_GET['id_caja']} AND medio_pago.id_medio_pago={$_GET['id_pago']}";
            $data = (new ConsultaGlobal())->ConsultaGlobal($query_ingreso);
        } else {
            $query_egreso = "SELECT *,'" . RUTA_ARCHIVO . "/archivo/".DOMINIO_ARCHIVO."' as ruta_archivo from egreso 
            INNER JOIN negocio USING (id_negocio)
            inner join tipo_egreso USING (id_tipo_egreso)
            LEFT JOIN boleta on boleta.id_negocio=negocio.id_negocio
            LEFT JOIN factura on factura.id_negocio=negocio.id_negocio
            LEFT JOIN nota_venta on nota_venta.id_negocio=negocio.id_negocio
            where id_caja={$_GET['id_caja']} AND egreso.id_tipo_egreso={$_GET['id_pago']} ";
            $data = (new ConsultaGlobal())->ConsultaGlobal($query_egreso);
        }
        $respuesta = [
            "tipo_documento" => $_GET['tipo_documento'],
            "data" => $data
        ];
        echo json_encode($respuesta);
    }
    public function CerrarCaja()
    {
        $caja = Caja::where('id_caja', $_POST['id_caja'])->first();
        $caja->fechacierre_caja = date('Y-m-d H:i:s');
        $caja->estado_caja = 0;
        $caja->save();
        echo json_encode("Caja Cerrado exitosamente");
    }

    public function EnviarCorreoElectronico()
    {
        $path_imagen = __DIR__ . '/../archivo/'.DOMINIO_ARCHIVO.'/imagenes/ahorro_farma.jpg';
        $imagen = base64_encode(file_get_contents($path_imagen));

        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        $respuesta = $this->InformacionCaja($DatosPost->id_caja);
        $caja = Caja::where('id_caja', $DatosPost->id_caja)
            ->leftjoin('staff', 'staff.id_staff', 'caja.id_staff')
            ->first();
        //FORMATO PDF
        ob_start();
        if ($DatosPost->formato == "TICKET") {
            require_once 'generar-pdf/pdf/TicketCaja.php';
            $correo = $DatosPost->Correo_ticket;
            $setpaper = array(0, 0, 221, 544);
        } else {
            require_once 'generar-pdf/pdf/ResumenCaja.php';
            $correo = $DatosPost->Correo_pdf;
            $setpaper = "A4";
        }
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper($setpaper);
        $dompdf->render();
        $output = $dompdf->output();
        // --------------------------
        //CUERPO EMAIL----------------------------
        $cuerpo = "Estimado <strong>Encargado</strong><br>
        $caja->nombre_staff $caja->apellidopaterno_staff  le adjunta el detalle de la caja del dia.<br><br>
        <strong>!Gracias por su preferencia!</strong>";
        ob_start();
        require_once 'generar-pdf/Email/FomatoCaja.php';
        $body = ob_get_clean();
        ////-------------------------------
        $mail = new PHPMailer(true);
        try {
            //Server settings
            // $mail->SMTPDebug = 0;                       // Enable verbose debug output
            $mail->isSMTP();
            // smtp.mandrillapp.com
            // smithxd118@gmail.com
            // a74dac0e781527e2e06bd66041783587-us14
            // Send using SMTP
            $mail->Host       =  Host;                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = Username;                         // SMTP username
            $mail->Password   =  Password;                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = Port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            // id=dbecf254af
            //Recipients
            //DESDE DONDE
            $mail->setFrom(Email, 'Venta Electronica');
            //PARA QUIEN
            $mail->addAddress($correo);
            // $mail->addAddress('rdurand@wilsoft.cl');
            //copia
            // $mail->addCC('smithxd118@gmail.com');
            // Add a recipient
            // $fichero = file_get_contents($output);
            $mail->addStringAttachment($output, "Detalle_Caja.pdf");
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = "Resumen de Caja $DatosPost->formato ";
            $mail->Body    = $body;
            $mail->CharSet = 'UTF-8';
            $mail->send();
            $respuesta = 'ok';
        } catch (Exception $e) {
            echo "Ubo un error al Enviar {$e->getMessage()})";
            http_response_code(403);
            exit();
        }

        echo json_encode($respuesta);
    }

    public function VerificarCajaAbierta(){
        $caja = Caja::leftjoin('usuario','usuario.id_staff','caja.id_staff')
        ->where('usuario.id_usuario', $_GET['id_usuario'])
        ->where('estado_caja',1)
        ->first();
       if (isset($caja)) {
            exit(http_response_code(404));
       } else{
            echo json_encode(false);
       }
    }
}
