<?php

class BoletaSunat
{

    private $datos;
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function enviarboleta()
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

        // PARA CLIENTE
        $cliente = array(
            'tipodoc'                       =>  $this->datos['client']['tipoDoc'],
            'nrodoc'                        =>  $this->datos['client']['numDoc'],
            'razon_social'                  =>  $this->datos['client']['rznSocial'],
            'direccion'                     =>  $this->datos['client']['address']['direccion'],
            'pais'                          =>  'PE'
        );

        $comprobante = array(
            'tipodoc'                       =>  '03',
            'serie'                         =>  $this->datos['serie'],
            'correlativo'                   =>  $this->datos['correlativo'],
            'fecha_emision'                 =>  date('Y-m-d'),
            'hora'                          =>  '00:00:00',
            'fecha_vencimiento'             =>  date('Y-m-d'),
            'moneda'                        =>  'PEN',
            'total_opgravadas'              =>  0.00,
            'total_opexoneradas'            =>  0.00,
            'total_opinafectas'             =>  0.00,
            'total_impbolsas'               =>  0.00,
            'total_opgratuitas_1'            => 0.00,
            'total_opgratuitas_2'            => 0.00,
            'igv'                           =>  0.00,
            'total'                         =>  0.00,
            'tota_texto'                    =>  ''
        );
        $detalle = $this->datos['details'];

        //inicializar variables para los totales
        $total_opgravadas = 0.00;
        $total_opexoneradas = 0.00;
        $total_opinafectas = 0.00;
        $total_opimpbolsas = 0.00;
        $total = 0.00;
        $igv = 0.00;
        $op_gratuitas1 = 0.00;
        $op_gratuitas2 = 0.00;
        foreach ($detalle as $key => $value) {
            if ($value['tipo_afectacion_igv'] == 10) { //opgravadas
                $total_opgravadas += $value['valor_total'];
            }

            if ($value['tipo_afectacion_igv'] == 20) { //opexoneradas
                $total_opexoneradas += $value['valor_total'];
            }

            if ($value['tipo_afectacion_igv'] == 20) { //opinafectas
                $total_opinafectas += $value['valor_total'];
            }
            $igv += $value['igv'];
            $total_opimpbolsas += $value['total_impuesto_bolsas'];
            $total += $value['importe_total'] + $total_opimpbolsas;
        }

        $comprobante['total_opgravadas'] = $total_opgravadas;
        $comprobante['total_opexoneradas'] = $total_opexoneradas;
        $comprobante['total_opinafectas'] = $total_opinafectas;
        $comprobante['total_impbolsas'] = $total_opimpbolsas;
        $comprobante['total_opgratuitas_1'] = $op_gratuitas1;
        $comprobante['total_opgratuitas_2'] = $op_gratuitas2;
        $comprobante['igv'] = $igv;
        $comprobante['total'] = $total;

        require_once('cpe40/cantidad_en_letras.php');
        $comprobante['total_texto'] = CantidadEnLetra($total);

        //PARTE 1: CREAR EL XML
        $nombreXML = $emisor['nrodoc'] . '-' . $comprobante['tipodoc'] . '-' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $rutaXML = 'cpe40/xml/boleta/';
        $rutaCRD = 'cpe40/cdr/boleta/';
        $rutaCertificadoDigital = 'cpe40/certificado_digital/';

        require_once('cpe40/api/api_genera_xml.php');
        $objXML = new api_genera_xml();
        $objXML->crea_xml_invoice($rutaXML . $nombreXML, $emisor, $cliente, $comprobante, $detalle);



        //PARTE 2: ENVIO CPE-SUNAT
        require_once('cpe40/api/api_cpe.php');
        $objCPE = new api_cpe();
        $estado_envio = $objCPE->enviar_invoice($emisor, $nombreXML, $rutaCertificadoDigital, $rutaXML, $rutaCRD);

        $respuesta = [
            "Estado" => $estado_envio['estado'],
            "Mensaje" => $estado_envio['estado_mensaje'],
            "HASH_CPE" => $estado_envio['hash_cpe'],
            "Descripcion" => $estado_envio['descripcion'],
            "Nota" => $estado_envio['nota'],
            "Error_codigo" => $estado_envio['codigo_error'],
            "Mensaje_error" => $estado_envio['mensaje_error'],
            "HTTP_CODE" => $estado_envio['http_code'],
            "OUTPUT" => $estado_envio['output'],
            'ruta_xml' => $rutaXML,
            'ruta_zip' => $rutaCRD
        ];
        return $respuesta;
    }
}
