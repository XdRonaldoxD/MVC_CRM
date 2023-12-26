<?php

use Dompdf\Dompdf;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Milon\Barcode\DNS2D;


require_once "models/Boleta.php";
require_once "models/Factura.php";
require_once "models/Folio.php";
require_once "models/Cliente.php";
require_once "models/ProductoHistorial.php";
require_once "models/Negocio.php";
require_once "models/NegocioDetalle.php";
require_once "models/Producto.php";
require_once "models/NotaVenta.php";
require_once "models/Ingreso.php";
require_once "models/Egreso.php";
require_once "models/Usuario.php";
require_once "models/StockProductoBodega.php";
require_once "models/EmpresaVentaOnline.php";
require_once "models/TipoAfectacion.php";
require_once "Helpers/helpers.php";
require_once "config/Helper.php";
require_once "cpe40/boleta_sunat.php";
require_once "cpe40/factura_sunat.php";

class NegocioController
{

    private $fechaactual;
    public function __construct()
    {
        $this->fechaactual = date('Y-m-d H:i:s');
    }
    public function GenerarNegocio()
    {
        $DatosPost = file_get_contents("php://input");
        $datos = json_decode($DatosPost);
        $listaMetodosPago = $datos->ListaMetodosPago;
        $Totales = $datos->Totales;
        $totalespagados = $datos->Totales_pagados;
        $informacionForm = $datos->informacionForm;
        $id_caja = $datos->id_caja;
        $tipo_documento = $informacionForm->tipo_documento;
        $staff = Usuario::select("staff.*")->where('id_usuario', $informacionForm->vendedor)
            ->join('staff', 'staff.id_staff', 'usuario.id_staff')
            ->first();
        //VERIFICANDO STOCK-----------------------------------
        foreach ($datos->ProductoSeleccionados as $elemento) {
            $producto = StockProductoBodega::where('id_producto', $elemento->id_producto)->where('id_bodega', $staff->id_bodega)->first();
            if (!$producto || $producto->total_stock_producto_bodega <= 0) {
                echo json_encode("Verificar stock producto");
                exit(http_response_code(404));
            }
        }
        //-----------------------------------------------------
        $EmpresaVentaOnline = EmpresaVentaOnline::leftjoin('certificado_digital_empresa', 'certificado_digital_empresa.id_empresa_venta_online', 'empresa_venta_online.id_empresa_venta_online')
            ->leftjoin('distrito', 'distrito.idDistrito', 'empresa_venta_online.idDistrito')
            ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
            ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
            ->where('empresa_venta_online.id_empresa_venta_online', $informacionForm->id_empresa)
            ->first();

        $cliente = Cliente::leftjoin('distrito', 'distrito.idDistrito', 'cliente.idDistrito')
            ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
            ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
            ->where('id_cliente', $informacionForm->cliente)->first();

        if ($tipo_documento === 'FACTURA') {
            $folio_documento = Folio::where('id_folio', 9)->first();
            $tipoDoc = "01";
            $serie = $folio_documento->serie_folio;
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
            $id_tipo_documento = 1;
        } elseif ($tipo_documento === 'BOLETA') {
            $folio_documento = Folio::where('id_folio', 6)->first();
            if ($cliente->dni_cliente == '00000000') {
                $tipoDoc_cliente = '0';
            } else {
                $tipoDoc_cliente = '1';
            }
            $tipoDoc = "03";
            $serie = $folio_documento->serie_folio;
            $datos_cliente = [
                "tipoDoc" => $tipoDoc_cliente,
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
            $id_tipo_documento = 1;
        } else {
            $folio_documento = Folio::where('id_folio', 17)->first();
            $tipoDoc = "";
            $serie = '';
            $datos_cliente = [
                "tipoDoc" => '',
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
            $id_tipo_documento = 4;
        }

        $details = [];
        $igv_porcentaje = 0.18;
        foreach ($datos->ProductoSeleccionados as $i => $element) {
            $tipoAfectacion = Producto::join('tipo_afectacion', 'tipo_afectacion.id_tipo_afectacion', 'producto.id_tipo_afectacion')
                ->where('id_producto', $element->id_producto)
                ->first();
            $cantidad = $element->cantidad_seleccionado;
            $precio = round($tipoAfectacion->precioventa_producto, 2);
            $igv_detalle = 0;
            $factor_porcentaje = 1;

            if ($tipoAfectacion->codigo == 10) {
                $precio = round($precio / (1 + $igv_porcentaje), 2); #Le sacamos el impuesto
                $igv_detalle = $precio * $cantidad * $igv_porcentaje;
                $factor_porcentaje = 1 + $igv_porcentaje;
            }
            $datositem = array(
                'id_producto'               => $element->id_producto,
                'item'                      =>  $i + 1,
                'codigo'                    =>  $element->codigo_producto,
                'descripcion'               =>  $element->glosa_producto,
                'cantidad'                  =>  $cantidad,
                'precio_unitario'           =>  round($precio * $factor_porcentaje, 2), //incluido todos los impuestos
                'valor_unitario'            =>  $precio, //no incluye impuestos
                'igv'                       =>  round($igv_detalle, 2), //cantidad*(precio unitario - valor unitario)
                'tipo_precio'               => ($tipoAfectacion->codigo == 10) ? '01' : '02', //01: onerosos lucran, 02: no onerosos, no lucran
                'porcentaje_igv'            =>  $igv_porcentaje * 100,
                'valor_total'               =>  round($precio * $cantidad, 2), //cantidad * precio unitario
                'importe_total'             =>  round($precio * $factor_porcentaje, 2) * $cantidad, //cantidad * valor unitario
                'unidad'                    =>  'NIU',
                'tipo_afectacion_igv'       =>  $tipoAfectacion->codigo,
                'codigo_tipo_tributo'       =>  $tipoAfectacion->codigo_afectacion, // Catálogo No. 05: Códigos de tipos de tributos CATALOGO
                'tipo_tributo'              =>  $tipoAfectacion->tipo_afectacion,
                'nombre_tributo'            =>  $tipoAfectacion->nombre_afectacion,
                'bolsa_plastica'            =>  'NO', //impuesto  ICBPER
                'total_impuesto_bolsas'     =>  0.00,
                'id_tipo_afectacion'          => $tipoAfectacion->id_tipo_afectacion
            );
            array_push($details, $datositem);
        }

        $clavecertificado = null;
        if ($EmpresaVentaOnline->clavearchivo_certificado_digital) {
            $mensaje_encriptado = base64_decode($EmpresaVentaOnline->clavearchivo_certificado_digital);
            $partes = explode('::', $mensaje_encriptado);
            $clavecertificado = openssl_decrypt($partes[0], 'aes-256-cbc', 'CERTIFICADO_DIGITAL_SUNAT_VALIDO', OPENSSL_RAW_DATA, $partes[1]);
        }


        $clave_sol_certificado = null;
        if ($EmpresaVentaOnline->clavesol_certificado_digital) {
            $clave_sol = base64_decode($EmpresaVentaOnline->clavesol_certificado_digital);
            $partes_clave = explode('::', $clave_sol);
            $clave_sol_certificado = openssl_decrypt($partes_clave[0], 'aes-256-cbc', 'CERTIFICADO_DIGITAL_SUNAT_VALIDO', OPENSSL_RAW_DATA, $partes_clave[1]);
        }
        $correlativo = $folio_documento->numero_folio;
        $data = array(
            //EMPRESA------------------------------------------------
            "clavecertificado" => $clavecertificado,
            "usuario_sol" =>  $EmpresaVentaOnline->usuariosol_certificado_digital,
            "clave_sol" => $clave_sol_certificado,
            //-------------------------------------------------------
            "ublVersion" => "2.1",
            "tipoOperacion" => "0101",
            "tipoDoc" => $tipoDoc,
            "serie" => $serie,
            "correlativo" => $correlativo,
            "fechaEmision" => $this->fechaactual,
            "formaPago" => [
                "moneda" => "PEN",
                "tipo" => "Contado"
            ],
            "tipoMoneda" => "PEN",
            "client" => $datos_cliente,
            "company" => [
                "ruc" => $EmpresaVentaOnline->ruc_empresa_venta_online,
                "razonSocial" => $EmpresaVentaOnline->razon_social_empresa_venta_online,
                "nombreComercial" => $EmpresaVentaOnline->nombre_empresa_venta_online,
                "address" => [
                    "direccion" =>  $EmpresaVentaOnline->direccion_empresa_venta_online ?  $EmpresaVentaOnline->direccion_empresa_venta_online : 'Av. Villa Nueva 221',
                    "provincia" =>  $EmpresaVentaOnline->provincia ?  $EmpresaVentaOnline->provincia : 'LIMA',
                    "departamento" => $EmpresaVentaOnline->departamento ? $EmpresaVentaOnline->departamento : 'LIMA',
                    "distrito" =>  $EmpresaVentaOnline->distrito ?  $EmpresaVentaOnline->distrito : 'LIMA',
                    "ubigueo" => "150101"
                ]
            ],
            "mtoOperGravadas" => $Totales->subtotal,
            "totalImpuestos" => $Totales->igv,
            "mtoImpVenta" => $Totales->total,
            "details" => $details,
            'listaMetodosPago' => $listaMetodosPago
        );

        $respuesta = [];
        if ($tipo_documento === 'BOLETA') {
            $boleta = new BoletaSunat($data);
            $respuesta = $boleta->enviarboleta();
        } elseif ($tipo_documento === 'FACTURA') {
            $factura = new FacturaSunat($data);
            $respuesta = $factura->enviarfactura();
        }
        if (isset($respuesta) && isset($respuesta['HTTP_CODE']) && $respuesta['HTTP_CODE'] !== 200 && ($tipo_documento === 'BOLETA' || $tipo_documento === 'FACTURA') && $respuesta['estado'] != 8 && empty($respuesta['Nota'])) {
            echo json_encode($respuesta);
            exit(http_response_code(404));
        }


        $data += [
            'apellidopaterno_staff' => $staff->apellidopaterno_staff,
            'apellidomaterno_staff' => $staff->apellidomaterno_staff,
            'nombre_staff' => $staff->nombre_staff,
            'efectivo_negocio' => $totalespagados->total_pagado,
            'vuelto_negocio' => $totalespagados->vuelto
        ];
        $pathNotaVenta = $this->enviarNegocioVenta($data, $tipo_documento, $EmpresaVentaOnline);
        $folio = Folio::where('id_folio', 2)->first();
        $negocio_crear = [
            'id_usuario' => $informacionForm->vendedor,
            'id_folio' => $folio->id_folio,
            'id_cliente' => $informacionForm->cliente,
            'fechacreacion_negocio' => $this->fechaactual,
            'numero_negocio' => $folio->numero_folio,
            'valor_negocio' => $Totales->total,
            'valorafecto_negocio' => $Totales->subtotal,
            'porcentajeiva_negocio' => $Totales->igv,
            'vigente_negocio' => 1,
            'efectivo_negocio' =>  $totalespagados->total_pagado,
            'vuelto_negocio' => $totalespagados->vuelto,
            'id_bodega' => $staff->id_bodega
        ];
        $folio->numero_folio += 1;
        $folio->save();
        $Negocio = Negocio::create($negocio_crear);
        $notificar_stock = array();
        foreach ($details as $elemento) {
            // RESTANDO STOCK DEL PRODUCTOS
            $stockbodega = StockProductoBodega::where('id_producto', $elemento['id_producto'])->where('id_bodega', $staff->id_bodega)->first();
            $stockActual = $stockbodega->total_stock_producto_bodega - $elemento['cantidad'];
            $stockbodega->total_stock_producto_bodega = $stockActual;
            $stockbodega->save();

            $producto_historial = [
                'id_usuario' => $informacionForm->vendedor,
                'id_tipo_movimiento' => 2,
                'id_bodega' => $staff->id_bodega,
                'id_producto' => $elemento['id_producto'],
                'cantidadmovimiento_producto_historial' => $elemento['cantidad'],
                'fecha_producto_historial' => $this->fechaactual,
                'id_tipo_documento'=>$id_tipo_documento,
                'comentario_producto_historial' => "$tipo_documento DE VENTA ELECTRONICA"
            ];
            ProductoHistorial::create($producto_historial);

            $negocio_detalle = [
                'id_negocio' => $Negocio->id_negocio,
                'id_producto' =>  $elemento['id_producto'],
                'valorneto_negocio_detalle' => $elemento['precio_unitario'],
                'iva_negocio_detalle' => $elemento['igv'],
                'total_negocio_detalle' => $elemento['valor_total'],
                'fechacreacion_negocio_detalle' => $this->fechaactual,
                'cantidad_negocio_detalle' => $elemento['cantidad'],
                'preciounitario_negocio_detalle' => $elemento['valor_unitario'],
                'id_tipo_afectacion' => $elemento['id_tipo_afectacion'],
                'id_bodega' => $staff->id_bodega
            ];
            if ($elemento['tipo_precio'] == 01) {
                $negocio_detalle += [
                    'valorafecto_negocio_detalle' => $elemento['precio_unitario']
                ];
            } else {
                $negocio_detalle += [
                    'valorexento_negocio_detalle' => $elemento['precio_unitario']
                ];
            }
            NegocioDetalle::create($negocio_detalle);

            //-----------------------------------
            //para el pusher Notificar
            // $elementos_pusher = [
            //     "id_producto" =>  $elemento->id_producto,
            //     "cantidad" => $stockActual
            // ];
            // array_push($notificar_stock, $elementos_pusher);
            //
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
                $datosBoleta = [
                    'id_usuario' =>  $informacionForm->vendedor,
                    'id_folio' => 6,
                    'numero_boleta' => $data['correlativo'],
                    'serie_boleta' => $data['serie'],
                    'valor_boleta' => $data['mtoOperGravadas'],
                    'fechacreacion_boleta' => $this->fechaactual,
                    'iva_boleta' => $data['totalImpuestos'],
                    'total_boleta' => $data['mtoImpVenta'],
                    'xml_boleta' => $respuesta['ruta_xml'],
                    'cdrzip_boleta' => $respuesta['ruta_zip'],
                    'estado_boleta' => 1,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'comentario_boleta' => $respuesta['Descripcion'],
                    'path_boleta' => $pathNotaVenta['path'],
                    'path_ticket_boleta' => $pathNotaVenta['path_ticket']
                ];
                $folio_documento->numero_folio += 1;
                $folio_documento->save();
                $boletacreado = Boleta::create($datosBoleta);
                $id_documento = $boletacreado->id_boleta;
                break;
            case 'FACTURA':
                //CREAMOS LA FACTURA------------------------------------------------------------------------------------------
                $datosFactura = [
                    'id_usuario' => $informacionForm->vendedor,
                    'id_folio' => 9,
                    'numero_factura' => $data['correlativo'],
                    'serie_factura' => $data['serie'],
                    'fechacreacion_factura' => $this->fechaactual,
                    'valorafecto_factura' => $data['mtoOperGravadas'],
                    'iva_factura' => $data['totalImpuestos'],
                    'total_factura' => $data['mtoImpVenta'],
                    'xml_factura' => $respuesta['ruta_xml'],
                    'cdrzip_factura' => $respuesta['ruta_zip'],
                    'estado_factura' => 1,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'comentario_factura' => $respuesta['Descripcion'],
                    'path_documento' => $pathNotaVenta['path'],
                    'path_ticket_factura' =>  $pathNotaVenta['path_ticket']
                ];
                $folio_documento->numero_folio += 1;
                $folio_documento->save();
                $facturacreado = Factura::create($datosFactura);
                $id_documento = $facturacreado->id_factura;
                break;
            default:
                $folio = Folio::where('id_folio', 17)->first();
                //CREAMOS LA NOTA VENTA------------------------------------------------------------------------------------------
                $datos = [
                    'id_usuario' => $informacionForm->vendedor,
                    'id_folio' => 17,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'numero_nota_venta' => $folio->numero_folio,
                    'fechacreacion_nota_venta' => $this->fechaactual,
                    'valor_nota_venta' => number_format($Totales->subtotal, 2),
                    'iva_nota_venta' => number_format($Totales->igv, 2),
                    'total_nota_venta' => number_format($Totales->total, 2),
                    'estado_nota_venta' => 1,
                    'saldo_nota_venta' => number_format($Totales->total, 2),
                    'urlpdf_nota_venta' => $pathNotaVenta['path'],
                    'urlticket_nota_venta' =>  $pathNotaVenta['path_ticket']
                ];
                $folio->numero_folio += 1;
                $folio->save();
                $notaVenta = NotaVenta::create($datos);
                $id_documento = $notaVenta->id_nota_venta;
                break;
        }

        foreach ($listaMetodosPago as $element) {
            $folioingreso = Folio::where('id_folio', 4)->first();
            $id_tipo_ingreso = 1;
            if ($element->id_medio_pago === "3") {
                $id_tipo_ingreso = 9;
            }
            if ($element->id_medio_pago === "4") {
                $id_tipo_ingreso = 7;
            }
            $data_ingreso = [
                'id_negocio' => $Negocio->id_negocio,
                'id_folio' => $folioingreso->id_folio,
                'id_medio_pago' => $element->id_medio_pago,
                'id_caja' => $id_caja,
                'id_tipo_ingreso' => $id_tipo_ingreso,
                'valor_ingreso' => $element->monto,
                'numero_ingreso' => $folioingreso->numero_folio,
                'comentario_ingreso' => "$tipo_documento ELECTRONICA",
                'estado_ingreso' => 1,
                'fechacreacion_ingreso' => $this->fechaactual,
            ];
            $folioingreso->numero_folio += 1;
            $folioingreso->save();
            Ingreso::create($data_ingreso);
        }
        if ($totalespagados->vuelto > 0) {
            $folioegreso = Folio::where('id_folio', 18)->first();
            $data = [
                'id_caja' => $id_caja,
                'id_folio' => $folioegreso->id_folio,
                'id_usuario' => $informacionForm->vendedor,
                'id_negocio' => $Negocio->id_negocio,
                'id_tipo_egreso' => 6,
                'numero_egreso' => $folioegreso->numero_folio,
                'fechacreacion_egreso' => $this->fechaactual,
                'valor_egreso' => $totalespagados->vuelto
            ];
            $folioegreso->numero_folio += 1;
            $folioegreso->save();
            Egreso::create($data);
        }

        $rutaspdf = [
            "ticket" => RUTA_ARCHIVO . "/archivo/$tipo_documento/{$pathNotaVenta['path_ticket']}",
            "pdf" => RUTA_ARCHIVO . "/archivo/$tipo_documento/{$pathNotaVenta['path']}"
        ];
        echo json_encode($rutaspdf);
    }
    public function enviarNegocioVenta($data, $tipo_documento, $EmpresaVentaOnline)
    {
        $serie = $data['serie'];
        $correlativo = $data['correlativo'];
        $total_afecto = $data['mtoOperGravadas'];
        $igv_total = $data['totalImpuestos'];
        $importe_total = $data['mtoImpVenta'];
        $vendedor_documento = $data['apellidopaterno_staff'] . ' ' . $data['apellidomaterno_staff'] . ' ' . $data['nombre_staff'];
        $fecha = date("Y-m-d H:i:s");
        $separaFecha = explode(" ", $fecha);
        $Fecha = explode("-", $separaFecha[0]);
        $filename = "Ticket_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $filename_documento = "Documento_" . $tipo_documento . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . ".pdf";
        $path = 'archivo/' . $tipo_documento;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $imagen = '';
        if ($EmpresaVentaOnline->urllogovertical_empresa_venta_online) {
            $imagen = base64_encode(file_get_contents($EmpresaVentaOnline->urllogovertical_empresa_venta_online));
        }
        $valorventa = $data['mtoImpVenta'];
        $fecha_creacion_venta = $data['fechaEmision'];
        $pagocliente_venta = $data['efectivo_negocio'];
        $vuelto_negocio = $data['vuelto_negocio'];
        $fecha_emision_dte = date('Y-m-d', strtotime($data['fechaEmision']));
        $qr = "$EmpresaVentaOnline->ruc_empresa_venta_online|{$data['tipoDoc']}|$serie|$correlativo|$igv_total|$importe_total|$separaFecha[0]|{$data['tipoDoc']}|{$data['client']['numDoc']}";
        $codigoBarra = base64_encode(file_get_contents((new \chillerlan\QRCode\QRCode())->render($qr)));
        $informacion_empresa = [
            "nombre_empresa" => $EmpresaVentaOnline->nombre_empresa_venta_online,
            "ruc" => $EmpresaVentaOnline->ruc_empresa_venta_online,
            "razonSocial" => $EmpresaVentaOnline->razon_social_empresa_venta_online,
            "direccion" =>  $EmpresaVentaOnline->direccion_empresa_venta_online,
            "departamento" =>  $EmpresaVentaOnline->departamento,
            "provincia" => $EmpresaVentaOnline->provincia,
            "distrito" => $EmpresaVentaOnline->distrito,
            'tipo_documento' => $tipo_documento
        ];
        $informacion_cliente = [
            "dni_cliente" => $data['client']['numDoc'],
            "ruc_cliente" => $data['client']['numDoc'],
            'nombre_cliente_completo' => $data['client']['rznSocial'],
            "direccion_cliente" => $data['client']['address']['direccion'],
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
        $alturaTotal = $this->calcularAlturaTotal(count($data['details']));
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper(array(0, 0, 200, $alturaTotal));
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
        $respuestadocumento = [
            "path_ticket" => $filename,
            "path" => $filename_documento,
        ];
        return $respuestadocumento;
    }

    function calcularAlturaTotal($cantidadProductos)
    {
        $alturatotal = 390;
        for ($i = 0; $i < $cantidadProductos; $i++) {
            $alturatotal += 15;
        }
        return $alturatotal;
    }
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
