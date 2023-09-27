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

$cabecera = array(
    'tipodoc'                       =>  'RA', //RC: RESUMEN COMPRO, RA: RESUMEN ANULACIONES
    'serie'                         =>  date('Ymd'),
    'correlativo'                   =>  1,
    'fecha_emision'                 =>  date('Y-m-d'),
    'fecha_envio'                   =>  date('Y-m-d')
);

$detalle = array();

$cant_comp = 20;

for ($i=1; $i <= $cant_comp ; $i++) { 
     $detalle[] = array(
        'item'                  =>  $i,
        'tipodoc'               =>  '01',
        'serie'                 =>  'F001',
        'correlativo'           =>  $i,
        'motivo'                =>  'ERROR EN EL DOCUMENTO'
     );
}

//CREAR EL XML DE RESUMEN DE ANULACIONES O COMUNICACION DE BAJA
require_once('./api/api_genera_xml.php');
$obj_xml = new api_genera_xml();

//Nombre XML: RUC-TIPO-SERIE-CORRELATIVO
$nombreXML = $emisor['nrodoc'] . '-' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'];

$rutaXML = 'xml/';

$obj_xml->CrearXmlBajaDocumentos($emisor, $cabecera, $detalle,  $rutaXML . $nombreXML);
echo '</br> 1. SE CREÓ EL XML DE RESUMEN DE ANULACIONES';

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

echo '</br> PARTE 2: ENVIO CPE-SUNAT';
echo '</br> Estado de envío: ' . $estado_envio['estado'];
echo '</br> Mensake: ' . $estado_envio['estado_mensaje'];
echo '</br> HASH_CPE: ' . $hash_cpe;
echo '</br> Descripción: ' . $estado_envio['descripcion'];
echo '</br> Nota: ' . $estado_envio['nota'];
echo '</br> Código de error: ' . $estado_envio['codigo_error'];
echo '</br> Mensaje de error: ' . $estado_envio['mensaje_error'];
echo '</br> HTTP CODE: ' . $estado_envio['http_code'];
echo '</br> OUTPUT: ' . $estado_envio['output']; 

?>