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

class LibroVentasController
{
    public function traerTipoDocumento()
    {
        echo TipoDocumento::where('vigente_tipo_documento', 1)->get();
    }
    public function exportarLibroVentas()
    {
        $request = json_decode($_POST['informacionForm']);
        $idusuario = $_POST['id_usuario'];
        $fechadesde = $request->fecha_desde . ' 00:00:00';
        $fechahasta = $request->fecha_hasta . ' 23:59:59';
        $tipodoc = $request->tipo_documento;
        $documento = '';
        $consulta = '';
        if (!empty($request->fecha_desde)) {
            $consulta .= " and negocio.fechacreacion_negocio>='$fechadesde'";
        }
        if (!empty($request->fecha_hasta)) {
            $consulta .= " and negocio.fechacreacion_negocio<='$fechahasta'";
        }
        switch ($tipodoc) {
            case '1':
                $consulta .= " and factura.id_factura is not null";
                $documento = "FACTURA";
                break;
            case '3':
                $consulta .= " and boleta.id_boleta is not null";
                $documento = "BOLETA";
                break;
            case '6':
                $consulta .= " and (boleta_nota_credito.id_boleta is not null or factura_nota_credito.id_factura is not null)";
                $documento = "NOTA CREDITO";
                break;
            default:
                $consulta .= '';
                break;
        }
        $encabezado = ["FECHA", "N° ANTECIÓN", "TIPO DOCUMENTO", "N° DOCUMENTO", "RUT EMPRESA", "NOMBRE CLIENTE", "EXENTO", "AFECTO", "IMPUESTO", 'IVA', "TOTAL"];
        $consultaglobal = "SELECT cliente.*,negocio.id_negocio,negocio.numero_negocio,negocio.fechacreacion_negocio,boleta.*,
        factura.*,nota_venta.*
        FROM negocio
        INNER JOIN cliente using (id_cliente)
        LEFT JOIN boleta using (id_negocio)
        LEFT JOIN factura using (id_negocio)
        LEFT JOIN nota_venta using (id_negocio)

        LEFT JOIN nota_credito as boleta_nota_credito on boleta_nota_credito.id_boleta=boleta.id_boleta
        LEFT JOIN nota_credito as factura_nota_credito on factura_nota_credito.id_factura=factura.id_factura
        where negocio.vigente_negocio=1 $consulta
        order by negocio.id_negocio  desc";
        $todos = (new ConsultaGlobal())->ConsultaGlobal($consultaglobal);
    
        // GUARDAMOS EL ARCHIVO  //TRAEMOS EL EXCEL DE LA CRECION DE PYTHON
        //PYTHON EXPORTANTO ARCHIVO
        $jsonarray = array();
        $jsontotales = array();
        foreach ($todos as  $elemento) {
            $texto = "ELECTRONICA";
            $tipodocumento = '';
            $exento = 0;
            $afecto = 0;
            $impuesto = 0;
            $iva = 0;
            $total = 0;
            $numerodoc = "";
            if ($elemento->id_boleta) {
                $tipodocumento = "BOLETA $texto";
                $iva = $elemento->iva_boleta;
                $total = $elemento->total_boleta;
                $numerodoc = $elemento->numero_boleta;
                $afecto = $elemento->valor_boleta;

                if (array_key_exists('BOLETA', $jsontotales)) {
                    $jsontotales['BOLETA']["CANTIDAD"]++;
                    $jsontotales['BOLETA']["AFECTOS"] += $afecto;
                    $jsontotales['BOLETA']["IVA"] += $iva;
                    $jsontotales['BOLETA']["TOTAL"] += $total;
                } else {
                    $jsontotales['BOLETA'] = array(
                        "CANTIDAD" => 1,
                        "AFECTOS" => $afecto,
                        "IVA" => $iva,
                        "TOTAL" => $total
                    );
                }
            }
            if ($elemento->id_factura) {
                $tipodocumento = "FACTURA $texto";
                $iva = $elemento->iva_factura;
                $total = $elemento->total_factura;
                $numerodoc = $elemento->numero_factura;
                $afecto = $elemento->valorafecto_factura;

                if (array_key_exists('FACTURA', $jsontotales)) {
                    $jsontotales['FACTURA']["CANTIDAD"]++;
                    $jsontotales['FACTURA']["AFECTOS"] += $afecto;
                    $jsontotales['FACTURA']["IVA"] += $iva;
                    $jsontotales['FACTURA']["TOTAL"] += $total;
                } else {
                    $jsontotales['FACTURA'] = array(
                        "CANTIDAD" => 1,
                        "AFECTOS" => $afecto,
                        "IVA" => $iva,
                        "TOTAL" => $total
                    );
                }
            }
            if ($elemento->id_nota_venta) {
                $tipodocumento = "NOTA VENTA";
                $iva = $elemento->iva_nota_venta;
                $total = $elemento->total_nota_venta;
                $numerodoc = $elemento->numero_nota_venta;
                $afecto = $elemento->valor_nota_venta;

                if (array_key_exists($tipodocumento, $jsontotales)) {
                    $jsontotales[$tipodocumento]["CANTIDAD"]++;
                    $jsontotales[$tipodocumento]["AFECTOS"] += $afecto;
                    $jsontotales[$tipodocumento]["IVA"] += $iva;
                    $jsontotales[$tipodocumento]["TOTAL"] += $total;
                } else {
                    $jsontotales[$tipodocumento] = array(
                        "CANTIDAD" => 1,
                        "AFECTOS" => $afecto,
                        "IVA" => $iva,
                        "TOTAL" => $total
                    );
                }
            }
            $jsonarray[] = array(
                $encabezado[0] => date('Y/m/d', strtotime($elemento->fechacreacion_negocio)),
                $encabezado[1] => $elemento->numero_negocio,
                $encabezado[2] => $tipodocumento,
                $encabezado[3] => $numerodoc,
                $encabezado[4] => '11111111',
                $encabezado[5] => $elemento->nombre_cliente . ' ' . $elemento->apellidopaterno_cliente . ' ' . $elemento->apellidomaterno_cliente,
                $encabezado[6] => $exento,
                $encabezado[7] => $afecto,
                $encabezado[8] => $impuesto,
                $encabezado[9] => $iva,
                $encabezado[10] => $total
            );
        }
        $jsonarray = json_encode($jsonarray);
        $file = fopen("datos$idusuario.txt", 'w');
        fwrite($file, $jsonarray);
        fclose($file);
        $jsontotales = json_encode($jsontotales);
        $file = fopen("datos_totales$idusuario.txt", 'w');
        fwrite($file, $jsontotales);
        fclose($file);

        // var_dump($todos);
        // exit();
        $segundacabecera = "$idusuario,$request->fecha_desde,$request->fecha_hasta,$documento";
        $comando = "python " . __DIR__ . "/../Helpers/exportarlibroVentas.py $segundacabecera";
        $respuesta = shell_exec($comando);
        if (empty($respuesta)) {
            echo "Error" . $respuesta;
            exit;
        }

        $inputFileName = __DIR__ . "/../datos$idusuario.xlsx";
        $datostext = __DIR__ . "/../datos$idusuario.txt";
        $totaltext = __DIR__ . "/../datos_totales$idusuario.txt";
        $spreadsheet = IOFactory::load($inputFileName);
        // Obtener el contenido del archivo Excel como una cadena de bytes y lo creamos en datos.txt
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $fileName = "Libro_venta_excel.xlsx";
        //ELIMINAMOS LOS ARCHIVOS-----------------------------------------
        unlink($inputFileName);
        unlink($datostext);
        unlink($totaltext);
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
