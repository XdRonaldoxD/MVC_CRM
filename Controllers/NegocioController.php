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
require_once "models/EmpresaVentaOnline.php";
require_once "Helpers/helpers.php";
require_once "config/Helper.php";

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
        $tipo_documento = $informacionForm->tipo_documento;
        $jsonArray = [];
        $EmpresaVentaOnline = EmpresaVentaOnline::leftjoin('certificado_digital_empresa', 'certificado_digital_empresa.id_empresa_venta_online', 'empresa_venta_online.id_empresa_venta_online')
            ->leftjoin('distrito', 'distrito.idDistrito', 'empresa_venta_online.idDistrito')
            ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
            ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
            ->where('empresa_venta_online.id_empresa_venta_online', $informacionForm->id_empresa)
            ->first();
        if ($tipo_documento === 'FACTURA' || $tipo_documento === 'BOLETA') {
            if ($tipo_documento === 'FACTURA') {
                $cliente = Cliente::leftjoin('distrito', 'distrito.idDistrito', 'cliente.idDistrito')
                    ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
                    ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
                    ->where('id_cliente', $informacionForm->cliente)->first();
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
            } elseif ($tipo_documento === 'BOLETA') {
                $cliente = Cliente::leftjoin('distrito', 'distrito.idDistrito', 'cliente.idDistrito')
                    ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
                    ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
                    ->where('id_cliente', $informacionForm->cliente)->first();
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

            $clave_certificado = null;
            if ($EmpresaVentaOnline->clavearchivo_certificado_digital) {
                $mensaje_encriptado = base64_decode($EmpresaVentaOnline->clavearchivo_certificado_digital);
                $partes = explode('::', $mensaje_encriptado);
                $clave_certificado = openssl_decrypt($partes[0], 'aes-256-cbc', 'CERTIFICADO_DIGITAL_SUNAT_VALIDO', OPENSSL_RAW_DATA, $partes[1]);
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
                "clave_certificado" => $clave_certificado,
                "usuario_sol" =>  $EmpresaVentaOnline->usuariosol_certificado_digital,
                "clave_sol" => $clave_sol_certificado,
                //-------------------------------------------------------
                "ublVersion" => "2.1",
                "tipoOperacion" => "0101",
                "tipoDoc" => $tipoDoc,
                "serie" => $serie,
                "correlativo" => $correlativo,
                "fechaEmision" => date('Y-m-d H:i:s') . "-05:00",
                "formaPago" => [
                    "moneda" => "PEN",
                    "tipo" => "Contado"
                ],
                "tipoMoneda" => "PEN",
                "client" => $datos_cliente,
                "company" => [
                    "ruc" => $EmpresaVentaOnline->ruc_empresa_venta_online,
                    "razonSocial" => $EmpresaVentaOnline->razon_social_empresa_venta_online,
                    "nombreComercial" => "",
                    "address" => [
                        "direccion" =>  $EmpresaVentaOnline->direccion_empresa_venta_online ?  $EmpresaVentaOnline->direccion_empresa_venta_online : 'Av. Villa Nueva 221',
                        "provincia" =>  $EmpresaVentaOnline->provincia ?  $EmpresaVentaOnline->provincia : 'LIMA',
                        "departamento" => $EmpresaVentaOnline->departamento ? $EmpresaVentaOnline->departamento : 'LIMA',
                        "distrito" =>  $EmpresaVentaOnline->distrito ?  $EmpresaVentaOnline->distrito : 'LIMA',
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



            $datosEmpresa = [
                'ruc_empresa' => $data['company']['ruc'],
                'usuario_sol' => $data['usuario_sol'],
                'clave_sol' => $data['clave_sol'],
                'clave_certificado' => $data['clave_certificado']
            ];
            $see = Helper::IdentificacionDocumentoPruebas();
            // $see = Helper::IdentificacionDocumentoProduccion($datosEmpresa);
            switch ($data['tipoDoc']) {
                case '01':
                    $documento = 'Factura';
                    break;
                case '03':
                    $documento = 'Boleta';
                    break;
                default:
                    $documento = '';
                    break;
            }

            // Cliente
            $client = (new Client())
                ->setTipoDoc($data['client']['tipoDoc'])
                ->setNumDoc($data['client']['numDoc'])
                ->setRznSocial($data['client']['rznSocial']);

            // Emisor
            $address = (new Address())
                ->setUbigueo($data['company']['address']['ubigueo'])
                ->setDepartamento($data['company']['address']['departamento'])
                ->setProvincia($data['company']['address']['provincia'])
                ->setDistrito($data['company']['address']['distrito'])
                ->setUrbanizacion('-')
                ->setDireccion($data['company']['address']['direccion'])
                ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

            $company = (new Company())
                ->setRuc($data['company']['ruc'])
                ->setRazonSocial($data['company']['razonSocial'])
                ->setNombreComercial($data['company']['nombreComercial'])
                ->setAddress($address);

            // Venta
            $invoice = (new Invoice())
                ->setUblVersion($data['ublVersion'])
                ->setTipoOperacion($data['tipoOperacion']) // Venta - Catalog. 51
                ->setTipoDoc($data['tipoDoc']) // Factura - Catalog. 01 
                ->setSerie($data['serie'])
                ->setCorrelativo($data['correlativo'])
                ->setFechaEmision(new DateTime($data['fechaEmision'])) // Zona horaria: Lima
                ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
                ->setTipoMoneda($data['formaPago']['moneda']) // Sol - Catalog. 02
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas($data['mtoOperGravadas'])
                ->setMtoIGV($data['mtoIGV'])
                ->setTotalImpuestos($data['totalImpuestos'])
                ->setValorVenta($data['valorVenta'])
                ->setSubTotal($data['subTotal'])
                ->setMtoImpVenta($data['mtoImpVenta']);
            $Datos_ventas = [];
            foreach ($data['details'] as $key => $element) {
                $item = (new SaleDetail())
                    ->setCodProducto($element['codProducto'])
                    ->setUnidad($element['unidad']) // Unidad - Catalog. 03
                    ->setCantidad($element['cantidad'])
                    ->setMtoValorUnitario($element['mtoValorUnitario'])
                    ->setDescripcion($element['descripcion'])
                    ->setMtoBaseIgv($element['mtoBaseIgv'])
                    ->setPorcentajeIgv($element['porcentajeIgv']) // 18%
                    ->setIgv($element['igv'])
                    ->setTipAfeIgv($element['tipAfeIgv']) // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos($element['totalImpuestos']) // Suma de impuestos en el detalle
                    ->setMtoValorVenta($element['mtoValorVenta'])
                    ->setMtoPrecioUnitario($element['mtoPrecioUnitario']);
                array_push($Datos_ventas, $item);
            }
            $legend = (new Legend())
                ->setCode($data['legends'][0]['code']) // Monto en letras - Catalog. 52
                ->setValue($data['legends'][0]['value']);

            $invoice->setDetails($Datos_ventas)
                ->setLegends([$legend]);

            $result = $see->send($invoice);


            // Verificamos que la conexión con SUNAT fue exitosa.
            if (!$result->isSuccess()) {
                // Mostrar error al conectarse a SUNAT.
                $mensajeError = $result->getError()->getMessage();
                $codigoError = $result->getError()->getCode();
                echo "Error: " . $mensajeError . "\n Código:" . $codigoError . "";
                exit(http_response_code(400));
            }
            $carpeta = __DIR__ . "/../archivo/$documento";
            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            }
            chmod($carpeta, 0777);

            file_put_contents($carpeta . '/' . $invoice->getName() . '.xml', $see->getFactory()->getLastXml());

            $cdrResponse = $result->getCdrResponse();
            $jsonArray = [
                "id" => $cdrResponse->getId(),
                "code" => $cdrResponse->getCode(),
                "description" => $cdrResponse->getDescription(),
                "notes" => $cdrResponse->getNotes(),
                "ruta_xml" => $carpeta . '/' . $invoice->getName() . '.xml',
                "ruta_zip" => $carpeta . '/' . 'R-' . $invoice->getName() . '.zip',
            ];
            // Guardamos el CDR
            file_put_contents($carpeta . '/' . 'R-' . $invoice->getName() . '.zip', $result->getCdrZip());


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
                    'xml_boleta' => $jsonArray['ruta_xml'],
                    'cdrzip_boleta' => $jsonArray['ruta_zip'],
                    'estado_boleta' => 1,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'comentario_boleta' => $jsonArray['description'],
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
                    'xml_factura' => $jsonArray['ruta_xml'],
                    'cdrzip_factura' => $jsonArray['ruta_zip'],
                    'estado_factura' => 1,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'comentario_factura' => $jsonArray['description'],
                ];
                $folio_documento->numero_folio += 1;
                $folio_documento->save();
                $factura_creado = Factura::create($Datos_Factura);
                $id_documento = $factura_creado->id_factura;
                break;
            default:
                $Folio = Folio::where('id_folio', 17)->first();
                //CREAMOS LA NOTA VENTA------------------------------------------------------------------------------------------
                $Datos_Factura = [
                    'id_usuario' => $informacionForm->vendedor,
                    'id_folio' => 17,
                    'id_negocio' => $Negocio->id_negocio,
                    'id_cliente' => $informacionForm->cliente,
                    'numero_nota_venta' => $Folio->numero_folio,
                    'fechacreacion_nota_venta' => date('Y-m-d H:i:s'),
                    'valor_nota_venta' => number_format($Totales->subtotal, 2),
                    'iva_nota_venta' => number_format($Totales->igv, 2),
                    'total_nota_venta' => number_format($Totales->total, 2),
                    'estado_nota_venta' => 1,
                    'saldo_nota_venta' => number_format($Totales->total, 2),
                ];
                $Folio->numero_folio += 1;
                $Folio->save();
                $NotaVenta = NotaVenta::create($Datos_Factura);
                $id_documento = $NotaVenta->id_nota_venta;
                break;
        }
        $pathNotaVenta = $this->EnviarNegocioVenta($Negocio->id_negocio, $tipo_documento, $EmpresaVentaOnline);
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
            $Folio_ingreso = Folio::where('id_folio', 4)->first();
            $id_tipo_ingreso = 1;
            if ($element->id_medio_pago === "3") {
                $id_tipo_ingreso = 9;
            }
            if ($element->id_medio_pago === "4") {
                $id_tipo_ingreso = 7;
            }
            $data_ingreso = [
                'id_negocio' => $Negocio->id_negocio,
                'id_folio' => $Folio_ingreso->id_folio,
                // 'id_comprobante_ingreso',
                'id_medio_pago' => $element->id_medio_pago,
                'id_caja' => $id_caja,
                'id_tipo_ingreso' => $id_tipo_ingreso,
                'valor_ingreso' => $element->monto,
                'numero_ingreso' => $Folio_ingreso->numero_folio,
                'comentario_ingreso' => "$tipo_documento ELECTRONICA",
                'estado_ingreso' => 1,
                'fechacreacion_ingreso' => date('Y-m-d H:i:s'),
            ];
            $Folio_ingreso->numero_folio += 1;
            $Folio_ingreso->save();
            Ingreso::create($data_ingreso);
        }
        if ($Totales_pagados->vuelto > 0) {
            $Folio_egreso = Folio::where('id_folio', 18)->first();
            $data = [
                'id_caja' => $id_caja,
                'id_folio' => $Folio_egreso->id_folio,
                'id_usuario' => $informacionForm->vendedor,
                'id_negocio' => $Negocio->id_negocio,
                'id_tipo_egreso' => 6,
                'numero_egreso' => $Folio_egreso->numero_folio,
                'fechacreacion_egreso' => date('Y-m-d H:i:s'),
                'valor_egreso' => $Totales_pagados->vuelto
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
    public function EnviarNegocioVenta($id_negocio, $tipo_documento, $EmpresaVentaOnline)
    {
        if ($tipo_documento === 'BOLETA') {
            $Boleta = Boleta::where('id_negocio', $id_negocio)
                ->join('usuario', 'usuario.id_usuario', 'boleta.id_usuario')
                ->join("staff", 'staff.id_staff', 'usuario.id_staff')
                ->first();
            $serie = $Boleta->serie_boleta;
            $correlativo = $Boleta->numero_boleta;
            $total_afecto = $Boleta->valor_boleta;
            $igv_total = $Boleta->iva_boleta;
            $importe_total = $Boleta->total_boleta;
            $vendedor_documento = $Boleta->apellidopaterno_staff . ' ' . $Boleta->apellidomaterno_staff . ' ' . $Boleta->nombre_staff;
        } else if ($tipo_documento === 'FACTURA') {
            $Factura = Factura::where('id_negocio', $id_negocio)
                ->join('usuario', 'usuario.id_usuario', 'factura.id_usuario')
                ->join("staff", 'staff.id_staff', 'usuario.id_staff')
                ->first();
            $serie = $Factura->serie_factura;
            $correlativo = $Factura->numero_factura;
            $total_afecto = $Factura->valorafecto_factura;
            $igv_total = $Factura->iva_factura;
            $importe_total = $Factura->total_factura;
            $vendedor_documento = $Factura->apellidopaterno_staff . ' ' . $Factura->apellidomaterno_staff . ' ' . $Factura->nombre_staff;
        } else {
            $NotaVenta = NotaVenta::where('id_negocio', $id_negocio)
                ->join('usuario', 'usuario.id_usuario', 'nota_venta.id_usuario')
                ->join("staff", 'staff.id_staff', 'usuario.id_staff')
                ->first();
            $serie = null;
            $correlativo = $NotaVenta->numero_nota_venta;
            $total_afecto = $NotaVenta->valor_nota_venta;
            $igv_total = $NotaVenta->iva_nota_venta;
            $importe_total = $NotaVenta->total_nota_venta;
            $vendedor_documento = $NotaVenta->apellidopaterno_staff . ' ' . $NotaVenta->apellidomaterno_staff . ' ' . $NotaVenta->nombre_staff;
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
