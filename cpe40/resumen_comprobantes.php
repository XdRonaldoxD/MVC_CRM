<?php

$emisor = array(

    'tipodoc'                       =>  '6',
    'nrodoc'                        =>  '20123456789',
    'razon_social'                  =>  'CETI ORG',
    'nombre_comercial'              =>  'CETI',
    'direccion'                     =>  'VIRTUAL',
    'ubigeo'                        =>  '130101',
    'departamento'                  =>  'LAMBAYEQUE',
    'provincia'                      =>  'CHICLAYO',
    'distrito'                      =>  'CHICLAYO',
    'pais'                          =>  'PE',
    'usuario_secundario'            =>  'MODDATOS',
    'clave_usuario_secundario'      =>  'MODDATOS',
);


//boletas, NC y ND que aplica referenciadas a boletas
$cabecera = array(
    'tipodoc'                       =>  'RC', //RC: RESUMEN COMPRO, RA: RESUMEN ANULACIONES
    'serie'                         =>  date('Ymd'),
    'correlativo'                   =>  1,
    'fecha_emision'                 =>  date('Y-m-d'),
    'fecha_envio'                   =>  date('Y-m-d')
);

$detalle = array();

$cant_comp = 500;

for ($i = 1; $i <= $cant_comp; $i++) {
    $item_total = rand(100, 900);
    $item_valor = number_format($item_total / 1.18, 2, '.', 1);
    $item_igv = $item_total - $item_valor;

    $detalle[] = array(
        'item'                  =>  $i,
        'tipodoc'               =>  '03',
        'serie'                 =>  'B001',
        'correlativo'           =>  $i,
        'tipodoci'               =>  '1',
        'numdoci'               =>  rand(10000000, 99999999),
        'condicion'             => rand(1, 3), //1:alta, 2:modificar, 3:baja
        'moneda'                =>  'PEN',
        'importe_total'         =>  $item_total,
        'valor_total'           =>  $item_valor,
        'igv_total'             =>  $item_igv,
        'tipo_total'            =>  '01',
        'codigo_afectacion'     =>  '1000',
        'nombre_afectacion'     =>  'IGV',
        'tipo_afectacion'       =>  'VAT'
    );
}

//CREAR EL XML DE RESUMEN DE COMPROBANTES/DIARIO
require_once('./api/api_genera_xml.php');
$obj_xml = new api_genera_xml();

//Nombre XML: RUC-TIPO-SERIE-CORRELATIVO
$nombreXML = $emisor['nrodoc'] . '-' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'];

$rutaXML = 'xml/';

$obj_xml->CrearXMLResumenDocumentos($emisor, $cabecera, $detalle,  $rutaXML . $nombreXML);
echo '</br> 1. SE CREÓ EL XML DE RESUMEN DE COMPROBANTES';

//ENVIO CPE-SUNAT
require_once('./api/api_cpe.php');
$obj_cpe = new api_cpe();
$estado_envio = $obj_cpe->enviar_resumen($emisor, $nombreXML, "certificado_digital/", 'xml/');
echo '</br> 2. SE ENVIA EL XML CPE-SUNAT';

echo '</br> NRO DE TICKET: ' . $estado_envio['ticket'];
$hash_cpe = $estado_envio['hash_cpe'];

if ($estado_envio['ticket'] > 0) {
    $estado_envio = $obj_cpe->consultar_ticket($emisor, $cabecera, $estado_envio['ticket']);
}

$respuesta = [
    "Estado" => $estado_envio['estado'],
    "Mensaje" => $estado_envio['estado_mensaje'],
    "HASH_CPE" => $hash_cpe,
    "Descripcion" => $estado_envio['descripcion'],
    "Nota" => $estado_envio['nota'],
    "Error_codigo" => $estado_envio['codigo_error'],
    "Mensaje_error" => $estado_envio['mensaje_error'],
    "HTTP_CODE" => $estado_envio['http_code'],
    "OUTPUT" => $estado_envio['output']
];
return $respuesta;
