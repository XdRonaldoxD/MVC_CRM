<?php

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once "Helpers/helpers.php";
require_once "models/Cliente.php";
require_once "models/Usuario.php";
require_once "models/Departamento.php";
require_once "models/Provincia.php";
require_once "models/Perfil.php";
require_once "models/Pedido.php";
require_once "models/PedidoDetalle.php";
require_once "models/Folio.php";
require_once "models/Producto.php";
require_once "models/ProductoHistorial.php";
require_once "models/AtributoProducto.php";
require_once "models/ConsultaGlobal.php";
require_once "models/ProductoColor.php";
require_once "models/PedidoDetalleAtributoProducto.php";
require_once "models/ProductoImagen.php";


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

    public function validarDNICliente()
    {
        $dni_cliente = $_GET['dni_cliente'];
        echo Cliente::where("dni_cliente", $dni_cliente)->exists();
    }
    public function correoUsuarioEnUso()
    {
        $e_mail_cliente = $_GET['e_mail_cliente'];
        echo Cliente::where("e_mail_cliente", $e_mail_cliente)->exists();
    }



    public function ActualizarPasswordCliente()
    {
        // [SEGURIDAD A1] Verifica la contraseña anterior (bcrypt o sha256 legado) en
        // PHP y guarda la nueva con bcrypt.
        $cliente =  Cliente::join('usuario', "usuario.id_cliente", "cliente.id_cliente")
            ->where('cliente.id_cliente',  $_POST['id_cliente'])
            ->first();
        if ($cliente && helpers::verifyPassword($_POST['password_anterior'], $cliente->password_usuario)) {
            $usuario = [
                "password_usuario" => helpers::hashPassword($_POST['password_actual'])
            ];
            Usuario::where("id_cliente", $_POST['id_cliente'])->update($usuario);
            echo json_encode("actualizado");
        } else {
            http_response_code(400);
            echo "Contraseña no válida";
        }
    }
    public function GuardarCliente()
    {
        $formulario = json_decode($_POST['formulario']);
        $existeCliente =  Cliente::join('usuario', "usuario.id_cliente", "cliente.id_cliente")
            ->where('dni_cliente', $formulario->dni_cliente)
            ->first();
        $apellidos_cliente = explode(" ", $formulario->apellidos_cliente);
        $datos = array(
            "dni_cliente" => $formulario->dni_cliente,
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
            "idDistrito" => $formulario->idDistrito
        );
        if (!isset($existeCliente)) {
            $nuevoCiente = Cliente::create($datos);
            if ($formulario->crearcuenta) {
                $contrasenia = helpers::hashPassword($formulario->password); // [SEGURIDAD A1] bcrypt
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
                'dni_cliente' => $formulario->dni_cliente,
                'success' => true,
            ];
        } else {
            Cliente::where("id_cliente", $formulario->id_cliente)->update($datos);
            $datos += [
                'id_cliente' => $formulario->id_cliente,
                "idDepartamento" => $formulario->idDepartamento,
                'id_usuario' => $existeCliente->id_usuario,
            ];
            $rpta = [
                'success' => true,
            ];
        }
        $sitio_cliente = Provincia::join('departamentos', 'departamentos.idDepartamento', "provincia.idDepartamento")
            ->where("provincia.idProvincia", $formulario->idProvincia)
            ->first();
        $datos += [
            "idProvincia" => $formulario->idProvincia,
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
        // [SEGURIDAD A1] Antes el password_verify estaba con los argumentos invertidos
        // y comparaba contra un sha256 (nunca funcionaba). Ahora verifica correctamente
        // (bcrypt o sha256 legado) y guarda la nueva contraseña con bcrypt.
        if ($data_usuario && helpers::verifyPassword($_POST['contrasenia_anterior'], $data_usuario->password_usuario)) {
            $data_usuario->password_usuario = helpers::hashPassword($_POST['contrasenia_actual']);
            $data_usuario->save();
            echo json_encode("Contraseña actualizado");
        } else {
            http_response_code(400);
            echo json_encode("Contraseña no válida");
        }
        return json_encode("exito");
    }

    public function LoginCliente()
    {

        // [SEGURIDAD A1] Login de cliente: se busca por email y se verifica en PHP
        // (bcrypt o sha256 legado), con rehash a bcrypt. El hash nunca se devuelve.
        $cliente = Cliente::join("usuario", "usuario.id_cliente", "cliente.id_cliente")
            ->leftjoin('distrito', 'distrito.idDistrito', 'cliente.idDistrito')
            ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
            ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
            ->where("cliente.e_mail_cliente", $_POST['e_mail_cliente'])
            ->select("cliente.*", 'distrito.idDistrito', 'departamentos.idDepartamento', 'provincia.idProvincia', "usuario.id_usuario", "usuario.password_usuario")
            ->first();
        if ($cliente && helpers::verifyPassword($_POST['password_usuario'], $cliente->password_usuario)) {
            if (helpers::passwordNeedsRehash($cliente->password_usuario)) {
                Usuario::where('id_usuario', $cliente->id_usuario)
                    ->update(['password_usuario' => helpers::hashPassword($_POST['password_usuario'])]);
            }
            unset($cliente->password_usuario);
            echo json_encode($cliente);
        } else {
            http_response_code(400);
            echo json_encode("Cliente no existe");
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
            ->leftjoin("distrito", "distrito.idDistrito", "cliente.idDistrito")
            ->leftjoin("provincia", "provincia.idProvincia", "distrito.idProvincia")
            ->leftjoin("departamentos", "departamentos.idDepartamento", "provincia.idDepartamento")
            ->leftjoin('producto_imagen', "producto_imagen.id_producto", 'producto.id_producto')
            ->where("producto_imagen.portada_producto_imagen", 1)
            ->where('pedido.id_pedido', $_GET['id_pedido'])
            // ->select("producto.*","pedido_detalle.valorneto_pedido_detalle","pedido_detalle.cantidad_pedido_detalle","pedido_detalle.fechacreacion_pedido_detalle",
            // "cliente.*","provincia.provincia","departamentos.departamento","pedido.*",'producto_imagen.path_producto_imagen')
            ->get()
            ->toArray();
        $item = [];

        foreach ($PedidoDetalle as $value) {
            $producto = ProductoImagen::where('id_producto', $value['id_producto'])
                ->where('portada_producto_imagen', 1)
                ->first();
            $element = [
                'id' => $value['id_producto'],
                'slug' => $value['urlamigable_producto'],
                'name' =>  $value['glosa_producto'],
                'image' => $producto['url_producto_imagen'],
                'price' => null,
                'quantity' => $value['cantidad_pedido_detalle'],
                'total' => $value['valortotal_pedido_detalle']
            ];
            array_push($item, $element);
        }
        $pedido = [
            'id' => $_GET['id_pedido'],
            'num_order' => $PedidoDetalle[0]['numero_pedido'],
            'date' => date('d/m/Y', strtotime($PedidoDetalle[0]['fechacreacion_pedido_detalle'])),
            'status' => $PedidoDetalle[0]['glosa_estado_pedido'],
            'items' => $item,
            'additionalLines' => [],
            'quantity' => null,
            'subtotal' => $PedidoDetalle[0]['valorneto_pedido'],
            'total' => $PedidoDetalle[0]['valortotal_pedido'],
            'igv' => $PedidoDetalle[0]['iva_pedido'],
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
                mkdir(__DIR__ . "/../../archivo/imagen_pedido", 0755, true);
            }
            $fechacreacion = date('Y-m-d');
            $Fecha = explode("-", $fechacreacion);

            // [SEGURIDAD C4] Validar la imagen antes de moverla a un directorio web.
            // Antes se conservaba la extensión enviada por el cliente (p. ej. ".php"),
            // permitiendo subir y ejecutar código arbitrario (RCE). Además se usaba
            // mt_srand() (que NO genera valor, devuelve null) para el nombre.
            $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $mimesPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!isset($_FILES['Imagen']) || $_FILES['Imagen']['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'No se recibió la imagen del comprobante.']);
                die;
            }
            if ($_FILES['Imagen']['size'] > 5 * 1024 * 1024) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'La imagen supera el tamaño máximo (5 MB).']);
                die;
            }
            $extension = strtolower(pathinfo($_FILES['Imagen']['name'], PATHINFO_EXTENSION));
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeReal = $finfo->file($_FILES['Imagen']['tmp_name']);
            if (!in_array($extension, $extensionesPermitidas, true) || !in_array($mimeReal, $mimesPermitidos, true)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Formato de imagen no permitido. Solo se aceptan imágenes (jpg, png, gif, webp).']);
                die;
            }
            // Nombre seguro generado por el servidor (nunca el nombre del cliente).
            $nombre = bin2hex(random_bytes(16)) . "_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . "." . $extension;
            $guardado = $_FILES['Imagen']['tmp_name'];
            if (!move_uploaded_file($guardado, __DIR__ . "/../../archivo/imagen_pedido/$nombre")) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar la imagen.']);
                die;
            }
            //PAGINA WEN (VERIFICAR IMAGEN)
            // https://sightengine.com/docs/getstarted
            $params = array(
                'media' => new CurlFile(__DIR__ . "/../../archivo/imagen_pedido/$nombre"),
                // 'url' =>  'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRMvVEnlUtT6iEz3iHVfPvCgFerKEzxRmeMug&usqp=CAU',
                'models' => 'nudity,wad,gore',
                'api_user' => SIGHTENGINE_USER,
                'api_secret' => SIGHTENGINE_SECRET,
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
        $folio = Folio::where("id_folio", 11)->first();
        $numero_pedido = $folio->numero_folio + 1;
        $folio->numero_folio = $numero_pedido;
        $folio->save();

        $datos_pedido = [
            'id_usuario' => $respuesta_cliente['datos']['id_usuario'],
            'id_folio' => $folio->id_folio,
            'id_cliente' => $respuesta_cliente['datos']['id_cliente'],
            'id_estado_pedido' => 1,
            'id_estado_pago' => $id_estado_pago,
            'id_estado_preparacion' => 5,
            // Este
            // 'idProvincia' => $respuesta_cliente['datos']['idProvincia'],
            'fechacreacion_pedido' => date('Y-m-d H:i:s'),
            'numero_pedido' => $numero_pedido,
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

        $Productos = json_decode($_POST['productos']);
        $datos = [];
        $tempFolder = __DIR__ . "/../../archivo/imagen_temp";
        if (!file_exists($tempFolder)) { // Si no existe, crear la carpeta temp
            mkdir($tempFolder, 0777, true); // El tercer parámetro "true" crea carpetas recursivamente si no existen
        }
        foreach ($Productos as $key => $elemento) {
            $iva_pedido_detalle = $elemento->product->price / 1.18;
            $iva_pedido_detalle = $elemento->product->price - $iva_pedido_detalle;
            $datos = [
                'id_pedido' => $Pedido->id_pedido,
                'id_producto' => $elemento->product->id,
                'iva_pedido_detalle' => $iva_pedido_detalle,
                'valortotal_pedido_detalle' => $elemento->product->price,
                'cantidad_pedido_detalle' => $elemento->quantity,
                'fechacreacion_pedido_detalle' => date('Y-m-d H:i:s'),
                'orden_pedido_detalle' => $key + 1,
            ];
            $PedidoDetalle = PedidoDetalle::create($datos);
            //GUARDAMOS EL HISTORIAL Y LO RESTAMOS------------------------
            $ProductoHistorial = [
                'id_usuario' => $respuesta_cliente['datos']['id_usuario'],
                'id_tipo_movimiento' => 2,
                'id_producto' => $elemento->product->id,
                'cantidadmovimiento_producto_historial' => $elemento->quantity,
                'fecha_producto_historial' => date('Y-m-d H:i:s'),
                'comentario_producto_historial' => 'Venta en linea.',
            ];
            ProductoHistorial::create($ProductoHistorial);
            $producto = Producto::where('id_producto', $elemento->product->id)->first();
            $producto->stock_producto -= $elemento->quantity;
            $producto->save();
            $options = [];

            foreach ($elemento->atributo_producto as $key => $value) {
                $AtributoProducto = AtributoProducto::join('atributo', 'atributo.id_atributo', 'atributo_producto.id_atributo')
                    ->where('atributo_producto.id_atributo_producto', $value->id_atributo_producto)
                    ->first();
                $ProductoColor = ProductoColor::where('id_producto_color', $value->id_producto_color)->first();
                $AtributoProducto->stock_atributo -= $value->cantidad;
                $AtributoProducto->save();
                $fillable = [
                    'id_pedido_detalle' => $PedidoDetalle->id_pedido_detalle,
                    'id_atributo' => $AtributoProducto->id_atributo,
                    'hexadecimal_producto_color' => $ProductoColor->hexadecimal_producto_color,
                    'nombre_color_detalle_atributo_producto' => $ProductoColor->nombre_producto_color,
                    'cantidad_pedido_detalle_atributo_producto' => $value->cantidad
                ];
                PedidoDetalleAtributoProducto::create($fillable);
                $option = [
                    'label' => 'Color',
                    'value' => $ProductoColor->nombre_producto_color,
                    'label_atributo' => 'Talla',
                    'value_atributo' => $AtributoProducto->glosa_atributo,
                ];
                array_push($options, $option);
            }
            // -----------------------------------------------------------------
            $elemento->options = $options;
        }
        $img_customer_service= __DIR__ . "/../../archivo/imagen_pedido/cliente_servicio.png";
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
            $mail->Host       = 'smtp-relay.sendinblue.com';                  // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'smithxd118@gmail.com';                     // SMTP username
            $mail->Password   = 'SwaYhJCFkX8MdWIZ';                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            // id=dbecf254af
            //Recipients
            //DESDE DONDE
            $mail->setFrom('lunaabanyovanna@gmail.com', 'BOTICA ROSA');
            //PARA QUIEN
            $mail->addAddress($respuesta_cliente['datos']['e_mail_cliente']);
            // Copia oculta
            // $mail->addBCC('smithxd108@gmail.com');
            $mail->addBCC('lunaabanyovanna@gmail.com');
            $mail->addBCC('rdurand@wilsoft.cl');
            // $mail->addAttachment($output);    // Add a recipient
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'COMPRA EN LINEA BOTICA ROSA';

            //AGREGAMOS LA IMAGEN DE LA SEÑORITA CLIENTE AL SERVICIO
            
            $cid = 'cliente_service'; // Identificador único para la imagen
            $mail->addEmbeddedImage($img_customer_service, $cid);
            $html = str_replace('<img alt="" src="cliente_service"  class="CToWUd">', '<img style="display: block; max-width: 100%;height: auto;" alt="" src="cid:' . $cid . '"  class="CToWUd">', $html);

            //------------------------------------------------------

            // Iterar sobre los productos y adjuntar imágenes
            foreach ($Productos as $key => $elemento) {
                $imagePath = $elemento->product->images[0];// Obtener la imagen del producto
                $tempImagePath = $tempFolder . '/' . basename($imagePath);
                file_put_contents($tempImagePath, file_get_contents($imagePath));// Descargar la imagen y guardarla temporalmente en el servidor
                $cid = 'imagen' . $key; // Identificador único para la imagen
                $mail->addEmbeddedImage($tempImagePath, $cid);
                // Reemplazar la etiqueta <img> en el HTML con el CID de la imagen incrustada
                $html = str_replace('<img alt="" src="' . $key . '" width="100" class="CToWUd">', '<img alt="" src="cid:' . $cid . '" width="100" class="CToWUd">', $html);
            }
            // Establecer el cuerpo del correo electrónico
            $mail->msgHTML($html);
            $mail->CharSet = 'UTF-8';
            $mail->send();
            $respuesta = 'ok';
        } catch (Exception $e) {
            echo "Ubo un error al Enviar {$e->getMessage()})";
            http_response_code(400);
            die;
        }

        // Eliminar las imágenes temporales después de enviar el correo
        foreach ($Productos as $key => $elemento) {
            $tempImagePath =  $tempFolder . '/' . basename($elemento->product->images[0]);
            if (file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }
        }

        //ENVIAMOS LA LA NOTIFICACION AL WHASSAP DEL vendedor
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v15.0/100307912941870/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "messaging_product": "whatsapp",
                                        "to": "51931585523",
                                        "type": "template",
                                        "template": {
                                            "name": "comunicacion",
                                            "language": {
                                                "code": "es"
                                            },
                                            "components": [
                                                {
                                                    "type": "body",
                                                    "parameters": [
                                                        {
                                                            "type": "text",
                                                            "text": "Pedido:N° ' . $numero_pedido . ' Se ha realizado una compra en linea. Por favor revisar el pedido en el sistema."
                                                        }
                                                    
                                                    ]
                                                }
                                            ]
                                        }
                                    }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer EAATiujUfihcBO2W3sK1enOw7JHNBhPflapWYBFqsYg0fIK9CC5gzBqyphiQjrLAF1bMi2r9FuqbAeZCYVgTA0ZBkEfyRbx3ZBiqLmkD18hlcmZB5ZALkWiStOo9FuiChk1nqRgHOW3RUNhWlxtZA81z1Wz6tqszOFJi9ZCUyvsE6vZA8MQToT3ZAoiE6eUdQVbx7VklMLzGxQji6ZBzKtF',
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $respuestaDetalla = [
            "datos_pedido" => $datos_pedido,
            "datos_detalle_pedido" => $Productos,
            "cliente" => $respuesta_cliente['datos'],
            "respuesta" => "ok"
        ];
        echo json_encode($respuestaDetalla);
    }

    public function traerlimitePedidoCliente()
    {
        $limit = '';
        if (isset($_GET['limit'])) {
            $limit = "LIMIT {$_GET['limit']}";
        }
        $sql = "SELECT p.id_pedido AS id,
        p.numero_pedido AS num_order,
                    DATE_FORMAT(p.fechacreacion_pedido, '%d/%m/%Y %h:%i %p') AS date,
                       ep.glosa_estado_pedido AS status,
                       p.valortotal_pedido AS total,
                       (SELECT COUNT(*) FROM pedido_detalle pd WHERE pd.id_pedido = p.id_pedido) AS quantity
                FROM pedido p
                INNER JOIN estado_pedido ep ON p.id_estado_pedido = ep.id_estado_pedido
                WHERE p.id_cliente = {$_GET['id_cliente']}
                $limit";
        $pedido = (new ConsultaGlobal())->ConsultaGlobal($sql);
        echo json_encode($pedido);
    }
}
