<?php



require_once "models/ConsultaGlobal.php";
require_once "config/Helper.php";
require_once "models/EmpresaVentaOnline.php";
require_once "models/MotivoDevolucion.php";
require_once "models/Boleta.php";
require_once "models/Factura.php";
require_once "models/Ingreso.php";
require_once "models/NegocioDetalle.php";
require_once "models/NotaCredito.php";
require_once "models/Folio.php";
require_once "cpe40/nota_credito_sunat.php";
require_once "cpe40/nota_credito_sunat.php";
require_once "Controllers/NegocioController.php";

class AnularDocumentoController
{

    private $fechaactual;
    public function __construct()
    {
        $this->fechaactual = date('Y-m-d');
    }

    public function listaDocumentoElectronicos()
    {
        $datosPost = file_get_contents("php://input");
        $datosPost = json_decode($datosPost);
        if ($datosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $datosPost->length;
        }
        if (isset($datosPost->filtro_buscar)) {
            $buscar = $datosPost->filtro_buscar;
            $consulta = " and (CONCAT(boleta.serie_boleta,'-',boleta.numero_boleta) LIKE '%$buscar%' or
            CONCAT(factura.serie_factura,'-',factura.numero_factura) LIKE '%$buscar%') ";
        } else {
            $consulta = '';
        }
        $query = "SELECT negocio.id_negocio,boleta.id_boleta,factura.id_factura,factura.serie_factura,factura.numero_factura,factura.fechacreacion_factura,
        boleta.serie_boleta,boleta.numero_boleta,boleta.fechacreacion_boleta,
        cliente_boleta.nombre_cliente as nombre_cliente_boleta,
        cliente_factura.nombre_cliente as nombre_cliente_factura
        FROM negocio
        LEFT JOIN boleta using (id_negocio)
        LEFT JOIN factura using (id_negocio)
        LEFT JOIN cliente as cliente_boleta on cliente_boleta.id_cliente=boleta.id_cliente
        LEFT JOIN cliente as cliente_factura on cliente_factura.id_cliente=factura.id_cliente
        WHERE negocio.vigente_negocio=1
        and (boleta.id_boleta is not null or factura.id_factura is not null)
        $consulta
        order by fechacreacion_negocio desc ";
        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $datosPost->start ";
        $consultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $datosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $consultaGlobal
        );
        echo json_encode($datos);
    }

    public function anularDocumentoElectronico()
    {
        //NOTA LAS FACTURAS SE PUEDE ANULAR X 7 DIAS DENTRO DEL MES
        //DIFERENCIAS DE ESOS DIAS ES RECOMENDAME UNA NOTA DE CREDITO
        //al igual que las boletas
        //POR NORMA TRIBUTARIA La facturas son 3 dias Y las boletas 7 por resumen -> en si son 9 dias
        //Y ahí viene el criterio, x ejemplo yo en mi sistema lo tengo a 7 días siempre y cuando sea dentro del mes
        // Si se emitió un 29 por ejemplo y el 2 de junio quiero bajarlo, ya no me deja y me dice mejor usa la nota de credito
        $datosanulacion = json_decode($_POST['datos_anulacion']);
        $empresaVentaOnline = empresaVentaOnline::leftjoin('certificado_digital_empresa', 'certificado_digital_empresa.id_empresa_venta_online', 'empresa_venta_online.id_empresa_venta_online')
            ->leftjoin('distrito', 'distrito.idDistrito', 'empresa_venta_online.idDistrito')
            ->leftjoin('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
            ->leftjoin('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
            ->where('empresa_venta_online.id_empresa_venta_online', 27)
            ->first();
        $motivodevolucion = MotivoDevolucion::where('id_motivo_devolucion', $datosanulacion->tipo_anulacion)->first();
        $datosEmpresa = [
            'ruc_empresa' => '10157622680',
            'usuario_sol' => 'CALEL019',
            'clave_sol' => 'Durand019',
            'clave_certificado' => 'durand019'
        ];
        // if ($empresaVentaOnline->id_certificado_digital) {
        //     $datosEmpresa += [
        //         'path_certificado_digital' => $empresaVentaOnline->path_certificado_digital,
        //     ];
        //     $see = Helper::identificacionDocumentoProduccion($datosEmpresa);
        // } else {
        $see = Helper::identificacionDocumentoPruebas();
        // }
        if ($_POST['tipo_documento'] === "BOLETA") {
            $boleta = Boleta::where('id_boleta', $_POST['id_documento'])
                ->join('cliente', 'cliente.id_cliente', 'boleta.id_cliente')
                ->first();
            $serie = $boleta->serie_boleta;
            $correlativo = $boleta->numero_boleta;
            $fechaEmision = date('Y-m-d', strtotime($boleta->fechacreacion_boleta));
            $tipoDoc = '03';
            if ($boleta->dni_cliente === '00000000') {
                $tipoDoccliente = '0';
            } else {
                $tipoDoccliente = '1';
            }
            $numDoc = $boleta->dni_cliente;
            $mtoBaseIgv = $boleta->valor_boleta;
            $mtoigv = $boleta->iva_boleta;
            $totalventa = $boleta->total_boleta;
            $id_negocio = $boleta->id_negocio;
            $cliente = $boleta;
            $datos_cliente = [
                "tipoDoc" => $tipoDoccliente,
                "numDoc" =>  $numDoc
            ];
        } else {
            $factura = Factura::where('id_factura', $_POST['id_documento'])
                ->join('cliente', 'cliente.id_cliente', 'boleta.id_cliente')
                ->first();
            $serie = $factura->serie_factura;
            $correlativo = $factura->numero_factura;
            $fechaEmision = date('Y-m-d', strtotime($factura->fechacreacion_factura));
            $tipoDoc = "01";
            $tipoDoccliente = '6';
            $numDoc = $factura->ruc_cliente;
            $mtoBaseIgv = $factura->valorafecto_factura;
            $mtoigv = $factura->iva_factura;
            $totalventa = $factura->total_factura;
            $id_negocio = $factura->id_negocio;
            $cliente = $factura;
            $datos_cliente = [
                "tipoDoc" => $tipoDoccliente,
                "numDoc" =>  $numDoc
            ];
        }
        $datos_cliente += [
            "rznSocial" => $cliente->nombre_cliente . ' ' . $cliente->apellidopaterno_cliente . ' ' . $cliente->apellidomaterno_cliente,
            "address" => [
                "direccion" => $cliente->direccion_cliente,
                "provincia" => $cliente->provincia,
                "departamento" => $cliente->departamento,
                "distrito" => $cliente->distrito,
                "ubigueo" => $cliente->ubigeo_cliente
            ]
        ];

        $detallenegocio = NegocioDetalle::join('tipo_afectacion', 'tipo_afectacion.id_tipo_afectacion', 'negocio_detalle.id_tipo_afectacion')
            ->join('producto', 'producto.id_producto', 'negocio_detalle.id_producto')
            ->where('id_negocio', $id_negocio)
            ->get();
        $details = [];
        $igv_porcentaje = 0.18;
        foreach ($detallenegocio as $i => $element) {
            $datositem = array(
                'id_producto'               => $element->id_producto,
                'item'                      =>  $i + 1,
                'codigo'                    =>  $element->codigo_producto,
                'descripcion'               =>  $element->glosa_producto,
                'cantidad'                  =>  $element->cantidad_negocio_detalle,
                'precio_unitario'           =>  $element->valorneto_negocio_detalle, //incluido todos los impuestos
                'valor_unitario'            =>  $element->preciounitario_negocio_detalle, //no incluye impuestos
                'igv'                       =>  $element->iva_negocio_detalle, //cantidad*(precio unitario - valor unitario)
                'tipo_precio'               => ($element->codigo == 10) ? "01" : "02", //01: onerosos lucran, 02: no onerosos, no lucran
                'porcentaje_igv'            =>  $igv_porcentaje * 100,
                'valor_total'               =>  round($element->preciounitario_negocio_detalle * $element->cantidad_negocio_detalle, 2), //cantidad * precio unitario
                'importe_total'             =>  $element->total_negocio_detalle, //cantidad * valor unitario
                'unidad'                    =>  'NIU',
                'tipo_afectacion_igv'       =>  $element->codigo,
                'codigo_tipo_tributo'       =>  $element->codigo_afectacion, // Catálogo No. 05: Códigos de tipos de tributos CATALOGO
                'tipo_tributo'              =>  $element->tipo_afectacion,
                'nombre_tributo'            =>  $element->nombre_afectacion,
                'bolsa_plastica'            =>  'NO', //impuesto  ICBPER
                'total_impuesto_bolsas'     =>  0.00,
            );
            array_push($details, $datositem);
        }
        $folioNotaCredito = Folio::where('id_folio', 8)->first();
        $data = array(
            "ublVersion" => "2.1",
            "tipoOperacion" => "0101",
            "tipoDoc" => $tipoDoc,
            "serie" => $folioNotaCredito->serie_folio,
            "correlativo" => $folioNotaCredito->numero_folio,
            "serie_ref" => $serie,
            "correlativo_ref" => $correlativo,
            "fechaEmision" => $fechaEmision,
            "client" => $datos_cliente,
            "company" => [
                "ruc" => $empresaVentaOnline->ruc_empresa_venta_online,
                "razonSocial" => $empresaVentaOnline->razon_social_empresa_venta_online,
                "nombreComercial" => $empresaVentaOnline->nombre_empresa_venta_online,
                "address" => [
                    "direccion" =>  $empresaVentaOnline->direccion_empresa_venta_online ?  $empresaVentaOnline->direccion_empresa_venta_online : 'Av. Villa Nueva 221',
                    "provincia" =>  $empresaVentaOnline->provincia ?  $empresaVentaOnline->provincia : 'LIMA',
                    "departamento" => $empresaVentaOnline->departamento ? $empresaVentaOnline->departamento : 'LIMA',
                    "distrito" =>  $empresaVentaOnline->distrito ?  $empresaVentaOnline->distrito : 'LIMA',
                    "ubigueo" => "150101"
                ]
            ],
            "details" => $details,
            "mtoOperGravadas" => $mtoBaseIgv,
            "mtoIGV" => $mtoigv,
            "valorVenta" => $totalventa,
            'codmotivo' => $motivodevolucion->codigo_devolucion,
            'descripcion' => $motivodevolucion->glosa_motivo_devolucion,

            "totalImpuestos" => $mtoigv,
            "mtoImpVenta" => $mtoBaseIgv,
        );


        $notaCreditoSunat = new NotaCreditoSunat($data);
        $respuesta = $notaCreditoSunat->enviarnotacredito();
        if (isset($respuesta['HTTP_CODE']) && $respuesta['HTTP_CODE'] !== 200 && $respuesta['estado'] != 8) {
            echo json_encode($respuesta);
            exit(http_response_code(404));
        }
        $staff = Usuario::select("staff.*")->where('id_usuario', $datosanulacion->id_usuario)
            ->join('staff', 'staff.id_staff', 'usuario.id_staff')
            ->first();
        $data += [
            'apellidopaterno_staff' => $staff->apellidopaterno_staff,
            'apellidomaterno_staff' => $staff->apellidomaterno_staff,
            'nombre_staff' => $staff->nombre_staff,
            'efectivo_negocio' => 0,
            'vuelto_negocio' => 0
        ];
        $negociocontroller = new NegocioController();
        $pathNotaCredito = $negociocontroller->enviarNegocioVenta($data, 'NOTA CREDITO', $empresaVentaOnline);
        $datos = [
            'id_folio' => 8,
            'id_usuario' => $datosanulacion->id_usuario,
            'numero_nota_credito' => $data['correlativo'],
            'serie_nota_credito' => $data['serie'],
            'fechacreacion_nota_credito' => date('Y-m-d H:i:s'),
            'valorafecto_nota_credito' => $data['mtoOperGravadas'],
            // 'valorexento_nota_credito',
            'iva_nota_credito' => $data['mtoIGV'],
            'total_nota_credito' => $data['valorVenta'],
            'estado_nota_credito' => $respuesta['Estado'],
            'zip_nota_credito' => $respuesta['ruta_zip'],
            'xml_nota_credito' => $respuesta['ruta_xml'],
            'path_nota_credito' => $pathNotaCredito['path'],
            'path_ticket_nota_credito' => $pathNotaCredito['path_ticket'],
            'id_motivo_devolucion' => $datosanulacion->tipo_anulacion,
            'respuesta_sunat_nota_credito' => json_encode($respuesta)
        ];
        if ($_POST['tipo_documento'] === "BOLETA") {
            $datos['id_boleta'] = $_POST['id_documento'];
        } else {
            $datos['id_factura'] = $_POST['id_documento'];
        }
        NotaCredito::create($datos);
        $folioNotaCredito->numero_folio += 1;
        $folioNotaCredito->save();
        $rutaspdf = [
            "ticket" => RUTA_ARCHIVO . "/archivo/NOTA CREDITO/{$pathNotaCredito['path_ticket']}",
            "pdf" => RUTA_ARCHIVO . "/archivo/NOTA CREDITO/{$pathNotaCredito['path']}"
        ];
        echo json_encode($rutaspdf);
    }

    public function traerDocumento()
    {
        if ($_GET['documento'] === "BOLETA") {
            $documento = Boleta::where('id_boleta', $_GET['id_documento'])
                ->join('usuario', 'usuario.id_usuario', 'boleta.id_usuario')
                ->join('negocio', 'negocio.id_negocio', 'boleta.id_negocio');
        } else {
            $documento = Factura::where('id_factura', $_GET['id_documento'])
                ->join('usuario', 'usuario.id_usuario', 'factura.id_usuario')
                ->join('negocio', 'negocio.id_negocio', 'factura.id_negocio');
        }
        $documento = $documento->join('staff', 'staff.id_staff', 'usuario.id_staff')
            ->first();

        $detallenegocio = NegocioDetalle::join('producto', 'producto.id_producto', 'negocio_detalle.id_producto')
            ->where('id_negocio', $documento->id_negocio)->get();
        $ingresos = Ingreso::where('id_negocio', $documento->id_negocio)
            ->join('medio_pago', 'medio_pago.id_medio_pago', 'ingreso.id_medio_pago')
            ->select('medio_pago.glosa_medio_pago', 'ingreso.valor_ingreso')
            ->get();
        $docu = '';
        if ($_GET['documento'] === "BOLETA" && $documento->id_boleta) {
            $docu = 'Boleta Electronica N °' . $documento->serie_boleta . '-' . $documento->numero_boleta;
        } else {
            $docu = 'Factura Electronica N °' . $documento->serie_factura . '-' . $documento->numero_factura;
        }
        $datosventa = [
            'documento' => $docu,
            'vendedor' => "{$documento->nombre_staff} {$documento->apellidopaterno_staff} {$documento->apellidomaterno_staff}",
            'id_usuario' => $documento->id_usuario,
            'forma_pagos' => $ingresos
        ];
        $respuesta = [
            'total' => isset($documento->id_boleta) ? $documento->total_boleta : $documento->total_factura,
            'igv' => isset($documento->id_boleta) ? $documento->iva_boleta : $documento->iva_factura,
            'subtotal' => isset($documento->id_boleta) ? $documento->valor_boleta : $documento->valorafecto_factura,
            'datos' => $detallenegocio,
            'datos_venta' => $datosventa
        ];
        echo json_encode($respuesta);
    }

    public function traerMotivoDevolucion()
    {
        echo MotivoDevolucion::where('vigente_motivo_devolucion', 1)->get();
    }
}
