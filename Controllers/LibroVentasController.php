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
require_once "Helpers/ScopeUsuario.php";

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
            $consulta .= " and negocio.fechacreacion_negocio>=" . ConsultaGlobal::esc($fechadesde); // [SEGURIDAD C3]
        }
        if (!empty($request->fecha_hasta)) {
            $consulta .= " and negocio.fechacreacion_negocio<=" . ConsultaGlobal::esc($fechahasta);
        }
        //NOTA CREDITO--------------------------------------------------
        $condicionesNotaCredito = [];
        if (!empty($request->fecha_desde)) {
            $condicionesNotaCredito[]= "fechacreacion_nota_credito>=" . ConsultaGlobal::esc($fechadesde); // [SEGURIDAD C3]
        }
        if (!empty($request->fecha_hasta)) {
            $condicionesNotaCredito[]= "fechacreacion_nota_credito<=" . ConsultaGlobal::esc($fechahasta);
        }
        // [SCOPE] No-admin: solo ventas y notas de crédito de su bodega (negocio.id_bodega
        // está 100% poblado; id_sucursal tiene nulos). Admin = sin filtro. La NC se ata a la
        // bodega del negocio de su boleta o factura de referencia.
        $idsBodega = ScopeUsuario::idsBodegas();
        if ($idsBodega !== null) {
            $inBod = implode(',', array_map('intval', $idsBodega));
            $consulta .= " and negocio.id_bodega IN ($inBod)";
            $condicionesNotaCredito[] = "(negocio_boleta.id_bodega IN ($inBod) OR negocio_factura.id_bodega IN ($inBod))";
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

        // [FIX] Antes este bloque escribía 2 JSON, llamaba al script de Python
        // (Helpers/python/exportarlibroVentas.py) por shell_exec y re-leía el xlsx.
        // Python NO corre en este entorno (devolvía error). Ahora el Excel se genera
        // 100% en PHP con PhpSpreadsheet. El script .py queda en disco, sin uso.
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Libro de Ventas');

        // Estilos basados en la paleta del sistema (azul institucional #1976D2).
        $fmtCabecera = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1976D2']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '000000']]],
        ];
        $fmtBorde = ['borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => '000000']]]];
        $MONEDA = '"S/ "#,##0.00';

        // --- Encabezado informativo ---
        $sheet->setCellValue('A1', 'LIBRO DE VENTAS');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('1976D2');
        $sheet->setCellValue('A2', 'Fecha de informe: ' . date('d/m/Y'));
        $sheet->setCellValue('A3', 'Tipo Documento: ' . ($documento !== '' ? $documento : 'TODOS'));
        $sheet->setCellValue('A4', 'Desde: ' . $request->fecha_desde . '   Hasta: ' . $request->fecha_hasta);
        $sheet->getStyle('A2:A4')->getFont()->getColor()->setRGB('8D97AD');

        // --- Tabla VENTAS (boleta / factura / nota venta) ---
        $cabVentas = 6;
        $fila = $cabVentas;
        foreach ($encabezado as $i => $titulo) {
            $sheet->setCellValueByColumnAndRow($i + 1, $fila, $titulo);
        }
        $sheet->getStyle('A' . $fila . ':J' . $fila)->applyFromArray($fmtCabecera);
        $fila++;
        $iniVentas = $fila;
        $resumen = []; // tipo_documento => [cantidad, exento, afecto, iva, total]
        $zb = 0;
        foreach ($jsonarray as $row) {
            $col = 1;
            foreach ($encabezado as $clave) {
                $valor = isset($row[$clave]) ? $row[$clave] : '';
                $sheet->setCellValueByColumnAndRow($col, $fila, $valor);
                $col++;
            }
            if ($zb % 2 == 1) {
                $sheet->getStyle('A' . $fila . ':J' . $fila)->getFill()->setFillType('solid')->getStartColor()->setRGB('F5F7FA');
            }
            $tipo = $row['TIPO DOCUMENTO'];
            if (!isset($resumen[$tipo])) { $resumen[$tipo] = [0, 0, 0, 0, 0]; }
            $resumen[$tipo][0] += 1;
            $resumen[$tipo][1] += (float) $row['EXENTO'];
            $resumen[$tipo][2] += (float) $row['AFECTO'];
            $resumen[$tipo][3] += (float) $row['IVA'];
            $resumen[$tipo][4] += (float) $row['TOTAL'];
            $fila++;
            $zb++;
        }
        $finVentas = $fila - 1;
        if ($finVentas >= $iniVentas) {
            $sheet->getStyle('A' . $cabVentas . ':J' . $finVentas)->applyFromArray($fmtBorde);
            $sheet->getStyle('G' . $iniVentas . ':J' . $finVentas)->getNumberFormat()->setFormatCode($MONEDA);
        }

        // --- Tabla NOTA DE CRÉDITO ---
        if (!empty($notacredito)) {
            $fila += 1;
            $cabNC = $fila;
            $ncCab = ['FECHA', 'TIPO DOCUMENTO', 'N° DOCUMENTO', 'SERIE', 'RUC EMPRESA', 'DOCUMENTO REFERENCIA', 'NOMBRE CLIENTE', 'N° DOC. REFERENCIA', 'SERIE REFERENCIA', 'EXENTO', 'AFECTO', 'IVA', 'TOTAL'];
            foreach ($ncCab as $i => $titulo) {
                $sheet->setCellValueByColumnAndRow($i + 1, $fila, $titulo);
            }
            $sheet->getStyle('A' . $fila . ':M' . $fila)->applyFromArray($fmtCabecera);
            $fila++;
            $iniNC = $fila;
            $resNC = [0, 0, 0, 0, 0];
            $zb = 0;
            foreach ($notacredito as $nc) {
                $valores = [
                    $nc->fechacreacion_nota_credito,
                    'NOTA CREDITO ELECTRONICO',
                    $nc->numero_nota_credito,
                    $nc->serie_nota_credito,
                    $empresa->ruc_empresa_venta_online,
                    $nc->documento_referencia,
                    $nc->cliente,
                    $nc->numero_referencia,
                    $nc->serie_referencia,
                    (float) $nc->valorexento_nota_credito,
                    (float) $nc->valorafecto_nota_credito,
                    (float) $nc->iva_nota_credito,
                    (float) $nc->total_nota_credito,
                ];
                $col = 1;
                foreach ($valores as $v) {
                    $sheet->setCellValueByColumnAndRow($col, $fila, $v);
                    $col++;
                }
                if ($zb % 2 == 1) {
                    $sheet->getStyle('A' . $fila . ':M' . $fila)->getFill()->setFillType('solid')->getStartColor()->setRGB('F5F7FA');
                }
                $resNC[0] += 1;
                $resNC[1] += (float) $nc->valorexento_nota_credito;
                $resNC[2] += (float) $nc->valorafecto_nota_credito;
                $resNC[3] += (float) $nc->iva_nota_credito;
                $resNC[4] += (float) $nc->total_nota_credito;
                $fila++;
                $zb++;
            }
            $finNC = $fila - 1;
            if ($finNC >= $iniNC) {
                $sheet->getStyle('A' . $cabNC . ':M' . $finNC)->applyFromArray($fmtBorde);
                $sheet->getStyle('J' . $iniNC . ':M' . $finNC)->getNumberFormat()->setFormatCode($MONEDA);
            }
            $resumen['NOTA CREDITO'] = $resNC;
        }

        // --- Resumen por tipo de documento + TOTAL ---
        $fila += 1;
        $sheet->setCellValue('A' . $fila, 'RESUMEN POR TIPO DE DOCUMENTO');
        $sheet->getStyle('A' . $fila)->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('1976D2');
        $fila++;
        $cabRes = $fila;
        $resCab = ['TIPO DOCUMENTO', 'CANTIDAD', 'EXENTO', 'AFECTO', 'IVA', 'TOTAL'];
        foreach ($resCab as $i => $titulo) {
            $sheet->setCellValueByColumnAndRow($i + 1, $fila, $titulo);
        }
        $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray($fmtCabecera);
        $fila++;
        $iniRes = $fila;
        $granTotal = 0;
        foreach ($resumen as $tipo => $r) {
            $sheet->setCellValueByColumnAndRow(1, $fila, $tipo);
            $sheet->setCellValueByColumnAndRow(2, $fila, $r[0]);
            $sheet->setCellValueByColumnAndRow(3, $fila, round($r[1], 2));
            $sheet->setCellValueByColumnAndRow(4, $fila, round($r[2], 2));
            $sheet->setCellValueByColumnAndRow(5, $fila, round($r[3], 2));
            $sheet->setCellValueByColumnAndRow(6, $fila, round($r[4], 2));
            $granTotal += $r[4];
            $fila++;
        }
        $sheet->setCellValueByColumnAndRow(1, $fila, 'TOTAL');
        $sheet->setCellValueByColumnAndRow(6, $fila, round($granTotal, 2));
        $sheet->getStyle('A' . $fila . ':F' . $fila)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E3F2FD']],
        ]);
        $sheet->getStyle('A' . $cabRes . ':F' . $fila)->applyFromArray($fmtBorde);
        $sheet->getStyle('C' . $iniRes . ':F' . $fila)->getNumberFormat()->setFormatCode($MONEDA);

        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = "Libro_venta_excel.xlsx";
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
