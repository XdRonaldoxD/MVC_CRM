<?php

class FacturaSunat
{
    private $datos;
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function enviarfactura()
    {
        $emisor = array(
            'tipodoc'                   =>  '6',//TIPO DOCUMETNO ESTE CASO RUC
            'nrodoc'                    =>  $this->datos['company']['ruc'],
            'razon_social'              =>  $this->datos['company']['razonSocial'],
            'nombre_comercial'          =>  $this->datos['company']['nombreComercial'], //DEBE IR UN NOMBRE CORTO
            'direccion'                 =>  $this->datos['company']['address']['direccion'],
            'ubigeo'                    =>  $this->datos['company']['address']['ubigueo'],
            'departamento'              =>  $this->datos['company']['address']['departamento'],
            'provincia'                 =>  $this->datos['company']['address']['provincia'],
            'distrito'                  =>  $this->datos['company']['address']['distrito'],
            'pais'                      =>  'PE',
            'usuario_secundario'        =>  $this->datos['usuario_sol'],
            'clave_usuario_secundario'  =>  $this->datos['clave_sol']
        );

        $cliente = array(
            'tipodoc'                   =>  $this->datos['client']['tipoDoc'],
            'nrodoc'                    =>  $this->datos['client']['numDoc'],
            'razon_social'              =>  $this->datos['client']['rznSocial'],
            'direccion'                 =>  $this->datos['client']['address']['direccion'],
            'pais'                      =>  'PE',
        );

        $comprobante = array(
            'tipodoc'                   =>  '01', //FACTURA: 01, BOLETA: 03, NC: 07, ND: 08
            'serie'                     =>  $this->datos['serie'],
            'correlativo'               =>  $this->datos['correlativo'],
            'fecha_emision'             =>  date('Y-m-d'),
            'hora'                      =>  '00:00:00',
            'fecha_vencimiento'         =>  date('Y-m-d'),
            'moneda'                    =>  'PEN',
            'total_opgravadas'          =>  0.00,
            'total_opexoneradas'        =>  0.00,
            'total_opinafectas'         =>  0.00,
            'total_impbolsas'           =>  0.00,
            'total_opgratuitas1'        =>  0.00,
            'total_opgratuitas2'        =>  0.00,
            'igv'                       =>  0.00,
            'total'                     =>  0.00,
            'total_texto'               =>  '',
            'forma_pago'                =>  'Contado', //Contado o Credito, si hay cuotas cuando es credito
            'monto_pendiente'           =>  0 //Contado: 0 y no hay cuotas
        );


        $detalle = $this->datos['details'];

        //inicializar varibles totales
        $total_opgravadas = 0.00;
        $total_opexoneradas = 0.00;
        $total_opinafectas = 0.00;
        $total_opimpbolsas = 0.00;
        $total = 0.00;
        $igv = 0.00;
        $op_gratuitas1 = 0.00;
        $op_gratuitas2 = 0.00;

        foreach ($detalle as $value) {
            if ($value['tipo_afectacion_igv'] == 10) { //op gravadas
                $total_opgravadas += $value['valor_total'];
            }
            if ($value['tipo_afectacion_igv'] == 20) { //op exoneradas
                $total_opexoneradas += $value['valor_total'];
            }
            if ($value['tipo_afectacion_igv'] == 30) { //op inafectas
                $total_opinafectas += $value['valor_total'];
            }
            $igv += $value['igv'];
            $total_opimpbolsas = $value['total_impuesto_bolsas'];
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

        $totalpagado = 0;
        foreach ($this->datos['listaMetodosPago'] as  $element) {
            $totalpagado += $element->monto;
        }
        $cuotas = [];

        if (round($totalpagado) != round($comprobante['total'])) {
            $comprobante['forma_pago'] = 'Credito';
            $monto_pendiente = $comprobante['total'] - $totalpagado;
            $comprobante['monto_pendiente'] = $monto_pendiente;

            // ESTOS SON EL MONTO QUE DEBE SI PAGA EN CUOTAS Y NO COMPLETO(FACTURA)
            $cuotas = array(
                array(
                    'cuota'                 =>  'Cuota001',
                    'monto'                 =>  100.00,
                    'fecha'                 =>  '2023-08-28'
                ),
                array(
                    'cuota'                 =>  'Cuota002',
                    'monto'                 =>  100.00,
                    'fecha'                 =>  '2023-09-28'
                )
            );
            //----------------------------------------------------------------------------------
        }

        require_once('cpe40/cantidad_en_letras.php');
        $comprobante['total_texto'] = CantidadEnLetra($total);

        //PARTE 1: CREAR EL XML DE FACTURA
        require_once('cpe40/api/api_genera_xml.php');
        $obj_xml = new api_genera_xml();

        //nombre del XML segun SUNAT
        $nombreXML = $emisor['nrodoc'] . '-' . $comprobante['tipodoc'] . '-' . $comprobante['serie'] . '-' . $comprobante['correlativo'];
        $rutaXML = 'cpe40/xml/'.DOMINIO_ARCHIVO;
        $rutaCRD = 'cpe40/cdr/'.DOMINIO_ARCHIVO;
        helpers::crearDirectorioSiNoExiste($rutaXML);
        helpers::crearDirectorioSiNoExiste($rutaCRD);
        $rutaXML = 'cpe40/xml/'.DOMINIO_ARCHIVO.'/factura/';
        $rutaCRD = 'cpe40/cdr/'.DOMINIO_ARCHIVO.'/factura/';
        helpers::crearDirectorioSiNoExiste($rutaXML);
        helpers::crearDirectorioSiNoExiste($rutaCRD);
        $rutaCertificadoDigital = 'cpe40/certificado_digital/'.DOMINIO_ARCHIVO."/".$this->datos['path_certificado_digital'];

        $obj_xml->crea_xml_invoice($rutaXML . $nombreXML, $emisor, $cliente, $comprobante, $detalle, $cuotas);
        //PARTE 2: ENVIO CPE A SUNAT
        require_once('cpe40/api/api_cpe.php');
        $objEnvio = new api_cpe();
        $estado_envio = $objEnvio->enviar_invoice($emisor, $nombreXML, $rutaCertificadoDigital, $rutaXML, $rutaCRD,$this->datos['clavecertificado']);
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
