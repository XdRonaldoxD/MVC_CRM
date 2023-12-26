<?php
class ResumenAnulacion
{
    private $datos;
    public function __construct($datos)
    {
        $this->datos = $datos;
    }
    public function anulardocumento()
    {
        $emisor = array(
            'tipodoc'                       =>  '6',
            'nrodoc'                        =>  '20123456789',
            'razon_social'                  =>  $this->datos['company']['razonSocial'],
            'nombre_comercial'              =>  $this->datos['company']['nombreComercial'], //DEBE IR UN NOMBRE CORTO
            'direccion'                     =>  $this->datos['company']['address']['direccion'],
            'ubigeo'                        =>  $this->datos['company']['address']['ubigueo'],
            'departamento'                  =>  $this->datos['company']['address']['departamento'],
            'provincia'                     =>  $this->datos['company']['address']['provincia'],
            'distrito'                      =>  $this->datos['company']['address']['distrito'],
            'pais'                          =>  'PE',
            'usuario_secundario'            =>  'MODDATOS',
            'clave_usuario_secundario'      =>  'MODDATOS',
        );

        $cabecera = array(
            'tipodoc'                       =>  $this->datos['tipodoc_anulado'], //RC: RESUMEN COMPRO, RA: RESUMEN ANULACIONES
            'serie'                         =>  $this->datos['serie'],
            'correlativo'                   =>  $this->datos['correlativo'],
            'fecha_emision'                 =>  date('Y-m-d'),
            'fecha_envio'                   =>  date('Y-m-d')
        );


        $detalle[] = array(
            'item'                  =>  1,
            'tipodoc'               =>  $this->datos['tipoDoc'],
            'serie'                 =>  $this->datos['serie_ref'],
            'correlativo'           =>  $this->datos['correlativo_ref'],
            'motivo'                =>  'ERROR EN EL DOCUMENTO'
        );


        //CREAR EL XML DE RESUMEN DE ANULACIONES O COMUNICACION DE BAJA
        require_once('cpe40/api/api_genera_xml.php');
        $obj_xml = new api_genera_xml();

        //Nombre XML: RUC-TIPO-SERIE-CORRELATIVO
        $nombreXML = $emisor['nrodoc'] . '-' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'];
        $rutaXML = 'cpe40/xml/anulacion/';
        $rutaCRD = 'cpe40/cdr/anulacion/';
        if (!file_exists($rutaXML)) {
            mkdir($rutaXML, 0777, true);
        }
        if (!file_exists($rutaCRD)) {
            mkdir($rutaCRD, 0777, true);
        }
        $rutaCertificadoDigital = 'cpe40/certificado_digital/';
        $obj_xml->CrearXmlBajaDocumentos($emisor, $cabecera, $detalle,  $rutaXML . $nombreXML);

        //ENVIO CPE-SUNAT
        require_once('cpe40/api/api_cpe.php');
        $obj_cpe = new api_cpe();
        $estado_envio = $obj_cpe->enviar_resumen($emisor, $nombreXML, $rutaCertificadoDigital,  $rutaXML);

        $hash_cpe = $estado_envio['hash_cpe'];
        $ruta_xml=$estado_envio['ruta_xml'];
        $ruta_zip=$estado_envio['ruta_zip'];
        if ($estado_envio['ticket'] > 0) {
            $estado_envio = $obj_cpe->consultar_ticket($emisor, $cabecera, $estado_envio['ticket'],$rutaCRD);
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
            "OUTPUT" => $estado_envio['output'],
            'ruta_xml' => $ruta_xml,
            'ruta_zip' => $ruta_zip,
        ];
        return $respuesta;
    }
}
