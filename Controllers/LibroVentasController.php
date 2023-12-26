<?php

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once "models/Producto.php";
require_once "models/Proveedor.php";
require_once "models/Marca.php";
require_once "models/TipoConcentracion.php";
require_once "models/TipoInventario.php";
require_once "models/TipoProductos.php";
require_once "models/Unidad.php";
require_once "models/ProductoHistorial.php";
require_once "models/ConsultaGlobal.php";
require_once "models/Categorias.php";
require_once "models/CategoriaProducto.php";
require_once "models/TipoDocumento.php";
require_once "models/EmpresaVentaOnline.php";

class LibroVentasController
{
    public function traerTipoDocumento()
    {
        echo TipoDocumento::where('vigente_tipo_documento', 1)->get();
    }
    public function exportarLibroVentas()
    {
        $request = json_decode($_POST['informacionForm']);
        $id_empresa = $_POST['id_empresa'];
        $idusuario = $_POST['id_usuario'];
        $fechadesde = $request->fecha_desde . ' 00:00:00';
        $fechahasta = $request->fecha_hasta . ' 23:59:59';
        $tipodoc = $request->tipo_documento;
        $documento = '';
        $consulta = '';
        $consultaNotaCredito='';
        if (!empty($request->fecha_desde)) {
            $consulta .= " and negocio.fechacreacion_negocio>='$fechadesde'";
        }
        if (!empty($request->fecha_hasta)) {
            $consulta .= " and negocio.fechacreacion_negocio<='$fechahasta'";
        }
        //NOTA CREDITO--------------------------------------------------
        $condicionesNotaCredito = [];
        if (!empty($request->fecha_desde)) {
            $condicionesNotaCredito[]= "fechacreacion_nota_credito>='$fechadesde'";
        }
        if (!empty($request->fecha_hasta)) {
            $condicionesNotaCredito[]= "fechacreacion_nota_credito<='$fechahasta'";
        }
        //---------------------------------------------------------------
        switch ($tipodoc) {
            case '1':
                $consulta .= " and factura.id_factura is not null";
                $documento = "FACTURA";
                break;
            case '3':
                $consulta .= " and boleta.id_boleta is not null";
                $documento = "BOLETA";
                break;
            default:
                $consulta .= '';
                break;
        }
        $encabezado = ["FECHA","TIPO DOCUMENTO", "N° DOCUMENTO", "SERIE","RUC EMPRESA", "NOMBRE CLIENTE", "EXENTO", "AFECTO", 'IVA', "TOTAL"];
        $consultaglobal = "SELECT
        cliente.*,
        negocio.id_negocio,
        negocio.numero_negocio,
        DATE_FORMAT(negocio.fechacreacion_negocio,'%d/%m/%Y') as fechacreacion_negocio,
        boleta.*,
        factura.*,
        nota_venta.*
        FROM negocio
        INNER JOIN cliente using (id_cliente)
        LEFT JOIN boleta using (id_negocio)
        LEFT JOIN factura using (id_negocio)
        LEFT JOIN nota_venta using (id_negocio)
        where negocio.vigente_negocio=1 $consulta
        order by negocio.id_negocio  desc";
        $todos = (new ConsultaGlobal())->ConsultaGlobal($consultaglobal);
        //NOTA CREDITO-----------------------------------------------------------------------------
        $notacredito=[];
        if ($tipodoc=='6' || empty($tipodoc)) {
            $wherenotacredito='';
            if (!empty($condicionesNotaCredito)) {
                $wherenotacredito = ' where '.implode(" AND ", $condicionesNotaCredito);
            }
            $consultaNotaCredito = "SELECT
            DATE_FORMAT(fechacreacion_nota_credito,'%d/%m/%Y') as fechacreacion_nota_credito,
            numero_nota_credito,
            serie_nota_credito,
            valorafecto_nota_credito,
            valorexento_nota_credito,
            iva_nota_credito,
            total_nota_credito,
            estado_nota_credito,
            nota_credito.id_boleta,
            nota_credito.id_factura,
            boleta.numero_boleta,
            boleta.serie_boleta,
            factura.numero_factura,
            factura.serie_factura,
            CONCAT(IFNULL(cliente_negocio_factura.nombre_cliente,''),' ',IFNULL(cliente_negocio_factura.apellidopaterno_cliente,''),' ',IFNULL(cliente_negocio_factura.apellidomaterno_cliente,'')) as cliente_negocio_factura,
            CONCAT(IFNULL(cliente_negocio_boleta.nombre_cliente,''),' ',IFNULL(cliente_negocio_boleta.apellidopaterno_cliente,''),' ',IFNULL(cliente_negocio_boleta.apellidomaterno_cliente,'')) as cliente_negocio_boleta
            FROM nota_credito
            LEFT JOIN boleta using (id_boleta)
            LEFT JOIN factura using (id_factura)
            LEFT JOIN negocio as negocio_boleta on negocio_boleta.id_negocio=boleta.id_negocio
            LEFT JOIN negocio as negocio_factura on negocio_factura.id_negocio=factura.id_negocio
            LEFT JOIN cliente as cliente_negocio_factura on cliente_negocio_factura.id_cliente=negocio_factura.id_cliente
            LEFT JOIN cliente as cliente_negocio_boleta on cliente_negocio_boleta.id_cliente=negocio_boleta.id_cliente
            $wherenotacredito
            order by nota_credito.id_nota_credito  desc";
            $notacredito=(new ConsultaGlobal())->ConsultaGlobal($consultaNotaCredito);
            foreach ($notacredito as &$value) {
                $cliente='';
                $numero_referencia='';
                $documento_referencia="";
                $serie_referencia="";
                if ($value->id_factura) {
                    $cliente=$value->cliente_negocio_factura;
                    $numero_referencia=$value->numero_factura;
                    $documento_referencia='FACTURA ELECTRONICA';
                    $serie_referencia=$value->serie_factura;
                }
                if ($value->id_boleta) {
                    $cliente=$value->cliente_negocio_boleta;
                    $numero_referencia=$value->numero_boleta;
                    $documento_referencia='BOLETA ELECTRONICA';
                    $serie_referencia=$value->serie_boleta;
                }
                $value->cliente=$cliente;
                $value->numero_referencia=$numero_referencia;
                $value->documento_referencia=$documento_referencia;
                $value->serie_referencia=$serie_referencia;
            }
        }
        //----------------------------------------------------------------------
     
        $empresa = EmpresaVentaOnline::where('id_empresa_venta_online', $id_empresa)->first();
        // GUARDAMOS EL ARCHIVO  //TRAEMOS EL EXCEL DE LA CRECION DE PYTHON
        //PYTHON EXPORTANTO ARCHIVO
        $jsonarray = array();
   
        foreach ($todos as  $elemento) {
            $texto = "ELECTRONICA";
            $tipodocumento = '';
            $exento = 0;
            $afecto = 0;
            $iva = 0;
            $total = 0;
            $numerodoc = "";
            $seriedoc='';
         
            if ($elemento->id_boleta) {
                $tipodocumento = "BOLETA $texto";
                $iva = $elemento->iva_boleta;
                $total = $elemento->total_boleta;
                $numerodoc = $elemento->numero_boleta;
                $seriedoc = $elemento->serie_boleta;
                $afecto = $elemento->valor_boleta;

            
            }
            if ($elemento->id_factura) {
                $tipodocumento = "FACTURA $texto";
                $iva = $elemento->iva_factura;
                $total = $elemento->total_factura;
                $numerodoc = $elemento->numero_factura;
                $seriedoc = $elemento->serie_factura;
                $afecto = $elemento->valorafecto_factura;

            }
            if ($elemento->id_nota_venta) {
                $tipodocumento = "NOTA VENTA";
                $iva = $elemento->iva_nota_venta;
                $total = $elemento->total_nota_venta;
                $numerodoc = $elemento->numero_nota_venta;
                $afecto = $elemento->valor_nota_venta;
            }
            $jsonarray[] = array(
                $encabezado[0] => $elemento->fechacreacion_negocio,
                $encabezado[1] => $tipodocumento,
                $encabezado[2] => $numerodoc,
                $encabezado[3] => $seriedoc,
                $encabezado[4] => $empresa->ruc_empresa_venta_online,
                $encabezado[5] => $elemento->nombre_cliente . ' ' . $elemento->apellidopaterno_cliente . ' ' . $elemento->apellidomaterno_cliente,
                $encabezado[6] => $exento,
                $encabezado[7] => $afecto,
                $encabezado[8] => $iva,
                $encabezado[9] => $total
            );
        }

        $jsonarray = json_encode($jsonarray);
        $file = fopen("datos$idusuario.json", 'w');
        fwrite($file, $jsonarray);
        fclose($file);

        $jsonarray = json_encode($notacredito);
        $file = fopen("datos_nota_credito$idusuario.json", 'w');
        fwrite($file, $jsonarray);
        fclose($file);
        $segundacabecera = "$idusuario,$request->fecha_desde,$request->fecha_hasta,$documento,$empresa->ruc_empresa_venta_online";
        $comando = "python " . __DIR__ . "/../Helpers/python/exportarlibroVentas.py $segundacabecera";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') { //SABER SI TIENN HTTPS SITIOS DE PRUDUCCION
            $activateCommand = ACTIVAR_COMANDO_PYTHON;
            $respuesta = shell_exec($activateCommand . ' && ' . $comando);
        } else {
            $respuesta = shell_exec($comando);
        }
        if (empty($respuesta)) {
            echo "Error" . $respuesta;
            exit;
        }
        $inputFileName = __DIR__ . "/../datos$idusuario.xlsx";
        $datostext = __DIR__ . "/../datos$idusuario.json";
        $totalnotacredito = __DIR__ . "/../datos_nota_credito$idusuario.json";
        $spreadsheet = IOFactory::load($inputFileName);
        // Obtener el contenido del archivo Excel como una cadena de bytes y lo creamos en datos.json
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $fileName = "Libro_venta_excel.xlsx";
        //ELIMINAMOS LOS ARCHIVOS-----------------------------------------
        unlink($inputFileName);
        unlink($datostext);
        unlink($totalnotacredito);
        //----------------------------------------------------------------
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
    }


    public function cellColor($cells, $color, $sheet)
    {
        $sheet->getStyle($cells)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => $color,
                ],
            ],
        ]);
    }
}
