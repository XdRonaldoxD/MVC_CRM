<?php

use Dompdf\Dompdf;
use Milon\Barcode\DNS2D;
use Symfony\Component\Console\Helper\Helper;

require_once "models/Boleta.php";
require_once "models/Factura.php";
require_once "models/Folio.php";
require_once "models/Cliente.php";
require_once "models/ProductoHistorial.php";
require_once "models/Negocio.php";
require_once "models/NegocioDetalle.php";
require_once "models/producto.php";
require_once "models/NotaVenta.php";
require_once "models/Ingreso.php";
require_once "models/Egreso.php";

class NegocioController
{

    public function __construct()
    {
    }
    public function GenerarNegocio()
    {
        $DatosPost = file_get_contents("php://input");
        $datos = json_decode($DatosPost);
        $ListaMetodosPago = $datos->ListaMetodosPago;
        $ProductoSeleccionados = $datos->ProductoSeleccionados;
        $Totales = $datos->Totales;
        $Totales_pagados = $datos->Totales_pagados;
        $informacionForm = $datos->informacionForm;
        $id_caja = $datos->id_caja;
        // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $serie = strtoupper(helpers::generate_string($permitted_chars, 3));
        $serie = "D01";
        $tipo_documento = $informacionForm->tipo_documento;
        if ($tipo_documento === 'FACTURA' || $tipo_documento === 'BOLETA') {
            if ($tipo_documento === 'FACTURA') {
                $cliente = Cliente::leftjoin('distrito', 'distrito.idDistrito', 'cliente.idDistrito')
                    ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
                    ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
                    ->where('id_cliente', $informacionForm->cliente)->first();
                $folio_documento = Folio::where('id_folio', 9)->first();
                $tipoDoc = "01";
                $serie = "F" . $serie;
                $datos_cliente = [
                    "tipoDoc" => "6",
                    "numDoc" =>  $cliente->ruc_cliente,
                    "rznSocial" =>  $cliente->nombre_cliente,
                    "address" => [
                        "direccion" =>  $cliente->direccion_cliente,
                        "provincia" => $cliente->provincia,
                        "departamento" => $cliente->departamento,
                        "distrito" => $cliente->distrito,
                        "ubigueo" => $cliente->ubigeo_cliente
                    ]
                ];
            } elseif ($tipo_documento === 'BOLETA') {
                $cliente = Cliente::leftjoin('distrito', 'distrito.idDistrito', 'cliente.idDistrito')
                    ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
                    ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
                    ->where('id_cliente', $informacionForm->cliente)->first();
                $folio_documento = Folio::where('id_folio', 6)->first();
                if ($cliente->dni_cliente=='00000000') {
                    $tipoDoc='0';
                }else{
                    $tipoDoc='1';
                }
                $tipoDoc = "03";
                $serie = "B" . $serie;
                $datos_cliente = [
                    "tipoDoc" => $tipoDoc,
                    "numDoc" =>  $cliente->dni_cliente,
                    "rznSocial" => $cliente->nombre_cliente . ' ' . $cliente->apellidopaterno_cliente . ' ' . $cliente->apellidomaterno_cliente,
                    "address" => [
                        "direccion" => $cliente->direccion_cliente,
                        "provincia" => $cliente->provincia,
                        "departamento" => $cliente->departamento,
                        "distrito" => $cliente->distrito,
                        "ubigueo" => $cliente->ubigeo_cliente
                    ]
                ];
            }

            $details = [];
            foreach ($ProductoSeleccionados as $key => $elemento) {
                $cantidad = $elemento->cantidad_seleccionado;
                $precio = number_format($elemento->precioventa_producto, 2);
                $mtoBaseIgv_precio = number_format($precio / (1 + 0.18), 2);
                $igv = number_format($precio - $mtoBaseIgv_precio, 2);
                $precio_sin_igv = number_format($precio - $igv, 2);
                $mtoValorVenta = number_format($precio_sin_igv * $cantidad, 2);
                $mtoBaseIgv = $mtoValorVenta;

                $total_venta = number_format($elemento->precio_venta_producto, 2);
                $total_venta_mtoBaseIgv = number_format($total_venta / (1 + 0.18), 2);
                $total_venta_igv = number_format($total_venta - $total_venta_mtoBaseIgv, 2);
                $total_venta_sin_igv = $total_venta - $igv;

                $elementos = [
                    "codProducto" => $elemento->codigo_producto,
                    "unidad" => "NIU",
                    "descripcion" => $elemento->glosa_producto,
                    "cantidad" => $cantidad,
                    "mtoValorUnitario" => $precio_sin_igv,
                    "mtoValorVenta" => $mtoValorVenta,
                    "mtoBaseIgv" => $mtoBaseIgv,
                    "porcentajeIgv" => 18,
                    "igv" => $total_venta_igv,
                    "tipAfeIgv" => 10,
                    "totalImpuestos" => $total_venta_igv,
                    "mtoPrecioUnitario" => $precio
                ];
                array_push($details, $elementos);
            }

            //CALCULOS PARA LA VENTA GLOBAL
            $total_venta_global = number_format($Totales->total, 2);
            $mtoBaseIgv_global = number_format($total_venta_global / (1 + 0.18), 2);
            $igv_global = number_format($total_venta_global - $mtoBaseIgv_global, 2);
            $precio_sin_igv_global = number_format($total_venta_global - $igv_global, 2);
            //------------------------------------------------

            //NOTA EL CORRELATIVO ES EL NUMERO DE FOLIO QUE AVANZA
            //PARA BOELTA ES B001-FACTURA ES F001
            // $cantidad_digito_folio = strlen($folio_documento->numero_folio);
            // $correlativo = "00000000";
            // $correlativo = substr($correlativo, 0, (8 - $cantidad_digito_folio));
            // $correlativo = $correlativo . $folio_documento->numero_folio;
            $correlativo = $folio_documento->numero_folio;
            $arregloJson = array(
                "ublVersion" => "2.1",
                "tipoOperacion" => "0101",
                "tipoDoc" => $tipoDoc,
                "serie" => $serie,
                "correlativo" => $correlativo,
                "fechaEmision" => date('Y-m-d') . "T00:00:00-05:00",
                "formaPago" => [
                    "moneda" => "PEN",
                    "tipo" => "Contado"
                ],
                "tipoMoneda" => "PEN",
                "client" => $datos_cliente,
                "company" => [
                    "ruc" => 90254813519,
                    "razonSocial" => "PRUEBA",
                    "nombreComercial" => "PRUEBA",
                    "address" => [
                        "direccion" => "Lima , L. 123",
                        "provincia" => "LIMA",
                        "departamento" => "LIMA",
                        "distrito" => "LIMA",
                        "ubigueo" => "150101"
                    ]
                ],
                "mtoOperGravadas" => $mtoBaseIgv_global,
                "mtoIGV" => $igv_global,
                "valorVenta" => $mtoBaseIgv_global,
                "totalImpuestos" => $igv_global,
                "subTotal" => $total_venta_global,
                "mtoImpVenta" => $total_venta_global,
                "details" => $details,
                "legends" => [
                    [
                        "code" => "1000",
                        "value" => "SON DOS CON 00/100 SOLES"
                    ]
                ]
            );

            $payload = json_encode($arregloJson);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://facturacion.apisperu.com/api/v1/invoice/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MjA0NDkxODcsImV4cCI6NDc3NDA0OTE4NywidXNlcm5hbWUiOiJ4cm9uYWxkbyIsImNvbXBhbnkiOiI5MDI1NDgxMzUxOSJ9.AqigLZMv7h7KQtC6ZUzXPJqbWdlr_ymmSCrqJeGUYu4WAe_gs16Vr0diNvK7qQFR9D87xIvzx68iviMOljqMH1WDkWM6CncplHhvuLeuG_ZobV3TKq5HbExSBtw8KTVE9R5gMDm_yQ_5uu1yw6sAwQduOF3LtmlegQaQjO0-1VezTD_gEqbT7ck5QGEdJq0ULemnlSszK8aZJE7b_cNvr_7n8Ha5of4P9VYtYfVgHa8D5JOezvODRtVauNsGRPxS4YzR35DTyPSJ_GNbvZ34lhm7BTxJqCXlXuxj081GBi2KdXWFt6XR603mOGQ2C6LKlatg95NYA3swooGvrwZHq3KF1oWghckfrKPieGZ3_p7N6O5_0dqHqUSsMLKcrY3de6y80WpOdyTkljVtyEJIhVn3CKyvL_-fNTz82qf71bLid18ozW7TGFAN6iGcRulHqWYVHBge_uAZa5lJGuThwKIIgR112rNM7tkoLVga1xd3juz9mPdCLPCaWC2HG6hB5J4bJA_afx3EvRGtRKrNZTuzmEcRdbYN1RBgvs6dRXQpmFr95ZnjdHPnJPRSlVHPC-ULkJd3Q6uegVrbYPpbIkNyY8Xa0Tm28_6PGFZYeabNw5AkRzRFxwR1oktQ9nyCLhi3539Gk_FD7noa86Vk5mNrnW5evSSACCDZ_YNOhUg',
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $jsonArray  = json_decode($response, true);
            if ($jsonArray['sunatResponse']['success'] == false) {
                http_response_code(404);
                echo  $response;
                exit();
            }
            // http_response_code(404);
            // echo  $response;
            // exit();
            //-----------------------------------------------------------------------------

        }

        $Folio = Folio::where('id_folio', 2)->first();
        $negocio_crear = [
            'id_usuario' => $informacionForm->vendedor,
            'id_folio' => $Folio->id_folio,
            'id_cliente' => $informacionForm->cliente,
            'fechacreacion_negocio' => date('Y-m-d H:i:s'),
            'numero_negocio' => $Folio->numero_folio,
            'valor_negocio' => $Totales_pagados->total_pagar,
            'vigente_negocio' => 1,
            // 'id_apertura_caja' => $this->request->id_aperturar_caja,
            'efectivo_negocio' =>  $Totales_pagados->total_pagar,
            'vuelto_negocio' => $Totales_pagados->vuelto,
        ];
        $Folio->numero_folio += 1;
        $Folio->save();
        $Negocio = Negocio::create($negocio_crear);
        $notificar_stock = array();
        foreach ($ProductoSeleccionados as $key => $elemento) {
            $cantidad = $elemento->cantidad_seleccionado;
            $precio = number_format($elemento->precio_venta_producto, 2);
            $precio_unitario = number_format($elemento->precioventa_producto, 2);
            $mtoBaseIgv = round($precio / (1 + 0.18), 2);
            $igv = $precio - $mtoBaseIgv;

            // RESTANDO STOCK DEL PRODUCTOS
            $producto_encontrado = producto::where('id_producto', $elemento->id_producto)->first();
            $stockActual = $producto_encontrado->stock_producto - $cantidad;
            $producto_encontrado->stock_producto = $stockActual;
            $producto_encontrado->save();
            $producto_historial = [
                'id_usuario' => $informacionForm->vendedor,
                'id_tipo_movimiento' => 2,
                'id_producto' => $elemento->id_producto,
                'cantidadmovimiento_producto_historial' => $cantidad,
                'fecha_producto_historial' => date('Y-m-d H:i:s'),
                'comentario_producto_historial' => "$tipo_documento DE VENTA ELECTRONICA"
            ];
            ProductoHistorial::create($producto_historial);
            //-----------------------------------
            //para el pusher Notificar
            // $elementos_pusher = [
            //     "id_producto" =>  $elemento->id_producto,
            //     "cantidad" => $stockActual
            // ];
            // array_push($notificar_stock, $elementos_pusher);
            //
            $negocio_detalle = [
                'id_negocio' => $Negocio->id_negocio,
                'id_producto' =>  $elemento->id_producto,
                'valorneto_negocio_detalle' => $mtoBaseIgv,
                'iva_negocio_detalle' => $igv,
                'total_negocio_detalle' => $precio,
                'fechacreacion_negocio_detalle' => date('Y-m-d H:i:s'),
                'cantidad_negocio_detalle' => $cantidad,
                'preciounitario_negocio_detalle' => $precio_unitario
                // 'preciounitario_negocio_detalle',
            ];
            NegocioDetalle::create($negocio_detalle);
        }
        // $pusher = Eventopusher::conectar();
        // $elementos = [
        //     "id_usuario" => $informacionForm->vendedor,
        //     "notificar_stock" => $notificar_stock
        // ];
        // $pusher->trigger('Stock', 'ActualizarStockEvent', $elementos);

        switch ($tipo_documento) {
            case 'BOLETA':
                //CREAMOS LA BOLETA-----------------------------------------------------------------------------------------
                $Datos_Boleta = [
                    'id_usuario' =>  $informacionForm->vendedor,
                    'id_folio' => 6,
                    'numero_boleta' => $correlativo,
                    'serie_boleta' => $serie,
                    'valor_boleta' => $precio_sin_igv_global,
                    'fechacreacion_boleta' => date('Y-m-d H:i:s'),
                    'iva_boleta' => $igv_global,
                    'total_boleta' => $total_venta_global,
                    'xml_boleta' => $jsonArray['xml'],
                    'cdrzip_boleta' => $jsonArray['sunatResponse']['cdrZip'],
                    'estado_boleta' => 1,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'comentario_boleta' => $jsonArray['sunatResponse']['cdrResponse']['description'],
                ];
                $folio_documento->numero_folio += 1;
                $folio_documento->save();
                $boleta_creado = Boleta::create($Datos_Boleta);
                $id_documento = $boleta_creado->id_boleta;
                //-
                break;
            case 'FACTURA':
                //CREAMOS LA FACTURA------------------------------------------------------------------------------------------
                $Datos_Factura = [
                    'id_usuario' => $informacionForm->vendedor,
                    'id_folio' => 9,
                    'numero_factura' => $correlativo,
                    'serie_factura' => $serie,
                    'fechacreacion_factura' => date('Y-m-d H:i:s'),
                    'valorafecto_factura' => $precio_sin_igv_global,
                    'iva_factura' => $igv_global,
                    'total_factura' => $total_venta_global,
                    'xml_factura' => $jsonArray['xml'],
                    'cdrzip_factura' => $jsonArray['sunatResponse']['cdrZip'],
                    'estado_factura' => 1,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'comentario_factura' => $jsonArray['sunatResponse']['cdrResponse']['description'],
                ];
                $folio_documento->numero_folio += 1;
                $folio_documento->save();
                $factura_creado = Factura::create($Datos_Factura);
                $id_documento = $factura_creado->id_factura;
                break;
            default:
                $Folio=Folio::where('id_folio',17)->first();
                //CREAMOS LA NOTA VENTA------------------------------------------------------------------------------------------
                $Datos_Factura = [
                    'id_usuario' => $informacionForm->vendedor,
                    'id_folio' => 17,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'numero_nota_venta'=>$Folio->numero_folio,
                    'fechacreacion_nota_venta'=>date('Y-m-d H:i:s'),
                    'valor_nota_venta'=>number_format($Totales->subtotal, 2),
                    'iva_nota_venta'=>number_format($Totales->igv, 2),
                    'total_nota_venta'=>number_format($Totales->total, 2),
                    'estado_nota_venta'=> 1,
                    'saldo_nota_venta'=>number_format($Totales->total, 2),
                ];
                $Folio->numero_folio += 1;
                $Folio->save();
                $NotaVenta = NotaVenta::create($Datos_Factura);
                $id_documento = $NotaVenta->id_nota_venta;
                break;
        }
        $pathNotaVenta = $this->EnviarNegocioVenta($Negocio->id_negocio, $tipo_documento);
        if ($tipo_documento === 'BOLETA') {
            $Boletas = Boleta::where('id_boleta', $id_documento)->first();
            $Boletas->path_boleta = $pathNotaVenta['path'];
            $Boletas->path_ticket_boleta = $pathNotaVenta['path_ticket'];
            $Boletas->save();
        } else if ($tipo_documento === 'FACTURA') {
            $Facturas = Factura::where('id_factura', $id_documento)->first();
            $Facturas->path_documento = $pathNotaVenta['path'];
            $Facturas->path_ticket_factura =  $pathNotaVenta['path_ticket'];
            $Facturas->save();
        } else {
            $NotaVenta = NotaVenta::where('id_nota_venta', $id_documento)->first();
            $NotaVenta->urlpdf_nota_venta = $pathNotaVenta['path'];
            $NotaVenta->urlticket_nota_venta =  $pathNotaVenta['path_ticket'];
            $NotaVenta->save();
        }

        foreach ($ListaMetodosPago as $key => $element) {
            $Folio_ingreso=Folio::where('id_folio',4)->first();
            $data_ingreso=[
                'id_negocio'=>$Negocio->id_negocio,
                'id_folio'=>$Folio_ingreso->id_folio,
                // 'id_comprobante_ingreso',
                'id_medio_pago'=>$element->id_medio_pago,
                'id_caja'=>$id_caja,
                'id_tipo_ingreso'=>1,
                'valor_ingreso'=>$element->monto,
                'numero_ingreso'=> $Folio_ingreso->numero_folio,
                'comentario_ingreso'=>"$tipo_documento ELECTRONICA",
                'estado_ingreso'=>1,
                'fechacreacion_ingreso'=>date('Y-m-d H:i:s'),
            ];
            $Folio_ingreso->numero_folio += 1;
            $Folio_ingreso->save();
           Ingreso::create($data_ingreso);
        }
        if ($Totales_pagados->vuelto>0) {
            $Folio_egreso=Folio::where('id_folio',18)->first();
            $data=[
                'id_caja'=>$id_caja,
                'id_folio'=>$Folio_egreso->id_folio,
                'id_usuario' => $informacionForm->vendedor,
                'id_negocio'=>$Negocio->id_negocio,
                'id_tipo_egreso'=>6,
                'numero_egreso'=>$Folio_egreso->numero_folio,
                'fechacreacion_egreso'=>date('Y-m-d H:i:s'),
                'valor_egreso'=>$Totales_pagados->vuelto
            ];
            $Folio_egreso->numero_folio += 1;
            $Folio_egreso->save();
            Egreso::create($data);
        }

        $rutaspdf = [
            "ticket" => RUTA_ARCHIVO . "/archivo/{$tipo_documento}Venta/{$pathNotaVenta['path_ticket']}",
            "pdf" => RUTA_ARCHIVO . "/archivo/{$tipo_documento}Venta/{$pathNotaVenta['path']}"
        ];
        echo json_encode($rutaspdf);
    }
    public function EnviarNegocioVenta($id_negocio, $tipo_documento)
    {
        if ($tipo_documento === 'BOLETA') {
            $Boleta = Boleta::where('id_negocio', $id_negocio)
                ->join('usuario', 'usuario.id_usuario', 'boleta.id_usuario')
                ->first();
            $serie = $Boleta->serie_boleta;
            $correlativo = $Boleta->numero_boleta;
            $total_afecto = $Boleta->valor_boleta;
            $igv_total = $Boleta->iva_boleta;
            $importe_total = $Boleta->total_boleta;
            $vendedor_documento = $Boleta->apellido_usuario . ' ' . $Boleta->nombre_usuario;
        } else if ($tipo_documento === 'FACTURA') {
            $Factura = Factura::where('id_negocio', $id_negocio)
                ->join('usuario', 'usuario.id_usuario', 'factura.id_usuario')
                ->first();
            $serie = $Factura->serie_factura;
            $correlativo = $Factura->numero_factura;
            $total_afecto = $Factura->valorafecto_factura;
            $igv_total = $Factura->iva_factura;
            $importe_total = $Factura->total_factura;
            $vendedor_documento = $Factura->apellido_usuario . ' ' . $Factura->nombre_usuario;
        } else {
            $NotaVenta = NotaVenta::where('id_negocio', $id_negocio)
                ->join('usuario', 'usuario.id_usuario', 'nota_venta.id_usuario')
                ->first();
            $serie = null;
            $correlativo = $NotaVenta->numero_nota_venta;
            $total_afecto = $NotaVenta->valor_nota_venta;
            $igv_total = $NotaVenta->iva_nota_venta;
            $importe_total = $NotaVenta->total_nota_venta;
            $vendedor_documento = $NotaVenta->apellido_usuario . ' ' . $NotaVenta->nombre_usuario;
        }
        $fecha = date("Y-m-d H:i:s");
        $separaFecha = explode(" ", $fecha);
        $Fecha = explode("-", $separaFecha[0]);
        $filename = "Ticket_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $filename_documento = "Documento_" . $tipo_documento . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $path = 'archivo/' . $tipo_documento . 'Venta';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $path_imagen = __DIR__ . '/../archivo/imagenes/ahorro_farma.jpg';
        $imagen = base64_encode(file_get_contents($path_imagen));
        $negocios = Negocio::join('negocio_detalle', 'negocio_detalle.id_negocio', 'negocio.id_negocio')
            ->join('producto', 'producto.id_producto', 'negocio_detalle.id_producto')
            ->join('cliente', 'cliente.id_cliente', 'negocio.id_cliente')
            ->where('negocio.id_negocio', $id_negocio)
            ->get();
        $valorventa = $negocios[0]['valor_negocio'];
        $fecha_creacion_venta = $negocios[0]['fechacreacion_negocio'];
        $pagocliente_venta = $negocios[0]['efectivo_negocio'];
        $vuelto_negocio = $negocios[0]['vuelto_negocio'];
        $fecha_emision_dte = date('Y-m-d', strtotime($negocios[0]['fechacreacion_negocio_detalle']));
        $codigoBarra = base64_encode(file_get_contents((new \chillerlan\QRCode\QRCode())->render($valorventa)));
        $informacion_empresa = [
            "nombre_empresa" => "AHORROFARMA",
            "ruc" => "10468481940",
            "razonSocial" => 'DORA YULITH REMIGIO ZELAYA',
            "direccion" => 'RB. LOS JARDINES MZ. C LT. 10',
            "departamento" => 'LIMA',
            "provincia" => 'HUAURA',
            "distrito" => 'SANTA MARIA',
            'tipo_documento' => $tipo_documento
        ];
        $informacion_cliente = [
            "dni_cliente" => $negocios[0]['dni_cliente'],
            "ruc_cliente" => $negocios[0]['ruc_cliente'],
            'nombre_cliente_completo' => $negocios[0]['nombre_cliente'] . ' ' . $negocios[0]['apellidopaterno_cliente'] . ' ' . $negocios[0]['apellidomaterno_cliente'],
            "direccion_cliente" => $negocios[0]['direccion_cliente'],

        ];
        $informacion_documento = [
            'serie' => $serie,
            'correlativo' => $correlativo,
            'vendedor_documento' => $vendedor_documento
        ];
        //CHAPO TODO EL CONTENIDO EN HTML
        ob_start();
        require_once 'generar-pdf/pdf/Negocioventa.php';
        $html = ob_get_clean();
        ////
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper(array(0, 0, 221, 544));
        // Render the HTML as PDF
        //GUARDAMOS EL DPF
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents($path . '/' . $filename, $output);

        //PARA EL PDF------------------------------------------------
        ob_start();
        require_once 'generar-pdf/pdf/DocumentoBoletaFactura.php';
        $html2 = ob_get_clean();
        $dompdf2 = new Dompdf();
        $dompdf2->loadHtml($html2);
        // (Optional) Setup the paper size and orientation
        $dompdf2->setPaper('A4');
        //GUARDAMOS EL DPF
        $dompdf2->render();
        $output2 = $dompdf2->output();
        file_put_contents($path . '/' . $filename_documento, $output2);
        // --------------------------------
        $respuesta_documento = [
            "path_ticket" => $filename,
            "path" => $filename_documento,
        ];
        return $respuesta_documento;
    }


    // public function VisualizarVentaTicket()
    // {
    //     $pathticket = $this->request->pathticket;
    //     $pathtoFile = RUTA_ARCHIVO . "/archivos/{$this->request->tipo_documento}Venta/$pathticket";
    //     echo json_encode($pathtoFile);
    // }

    // public function VisualizarVentaPdf()
    // {
    //     $pathpdf = $this->request->pathpdf;
    //     $pathtoFile = RUTA_ARCHIVO . "/archivos/{$this->request->tipo_documento}Venta/$pathpdf";
    //     echo json_encode($pathtoFile);
    // }
    public function VisualizarPdf()
    {

        if ($_POST['documento'] === 'BOLETA') {
            $boleta = Boleta::where('id_boleta', $_POST['id_documento'])->first();
            $pathpdf = $boleta->path_boleta;
            $pathticket = $boleta->path_ticket_boleta;
        } else {
            $factura = Factura::where('id_factura', $_POST['id_documento'])->first();
            $pathpdf = $factura->path_documento;
            $pathticket = $factura->path_ticket_factura;
        }

        $pathtoFile_pdf = RUTA_ARCHIVO . "/archivos/{$_POST['documento']}Venta/$pathpdf";
        $pathtoFile_ticket = RUTA_ARCHIVO . "/archivos/{$_POST['documento']}Venta/$pathticket";
        $respuesta = [
            'pdf' => $pathtoFile_pdf,
            'ticket' => $pathtoFile_ticket
        ];
        echo json_encode($respuesta);
    }
}
