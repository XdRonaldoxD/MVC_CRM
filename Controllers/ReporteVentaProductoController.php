<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require_once "models/ConsultaGlobal.php";
require_once "Helpers/ScopeUsuario.php";

class ReporteVentaProductoController
{
    public function tablaReporteVenta()
    {
        $datosPost = file_get_contents("php://input");
        $datosPost = json_decode($datosPost);
        if ($datosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $datosPost->length;
        }
        $buscar = $datosPost->search->value;
        $fecha_inicio = $datosPost->fecha_inicio;
        $fecha_fin = $datosPost->fecha_fin;
        $consulta = " and (glosa_producto LIKE " . ConsultaGlobal::esc('%' . $buscar . '%') . " or
        codigo_producto LIKE " . ConsultaGlobal::esc('%' . $buscar . '%') . " or codigo_barra_producto LIKE " . ConsultaGlobal::esc('%' . $buscar . '%') . " )";
        // [SCOPE] Un no-admin solo ve las ventas de su bodega; admin todas.
        $consulta .= ScopeUsuario::filtroBodega('negocio.id_bodega');
        $query = "SELECT ROUND(SUM(total_negocio_detalle),2) as total_negocio_detalle,glosa_producto,SUM(cantidad_negocio_detalle) as cantidad_negocio_detalle
        FROM negocio_detalle
        inner join producto using (id_producto)
        inner join negocio using (id_negocio)
        WHERE vigente_negocio=1  and fechacreacion_negocio_detalle>= " . ConsultaGlobal::esc($fecha_inicio . ' 00:00:00') . " and fechacreacion_negocio_detalle<=" . ConsultaGlobal::esc($fecha_fin . ' 23:59:59') . "
        $consulta
		GROUP BY id_producto
        ORDER BY id_negocio_detalle desc";

        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT " . (int) $longitud . " OFFSET " . (int) $datosPost->start . " ";
        $consultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $datosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $consultaGlobal
        );
        echo json_encode($datos);
    }

    public function exportarProductoVenta()
    {
        // [FIX] Generación del Excel 100% en PHP con PhpSpreadsheet. Antes se llamaba
        // a un script de Python por shell_exec (que no corre en este entorno y devolvía
        // un 404). Ahora se consulta y arma el xlsx directamente, sin dependencias.
        $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : date('Y-m-d');
        $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : date('Y-m-d');

        $query = "SELECT ROUND(SUM(total_negocio_detalle),2) as total_negocio_detalle, glosa_producto, SUM(cantidad_negocio_detalle) as cantidad_negocio_detalle
            FROM negocio_detalle
            inner join producto using (id_producto)
            inner join negocio using (id_negocio)
            WHERE vigente_negocio=1
            and fechacreacion_negocio_detalle >= " . ConsultaGlobal::esc($fecha_inicio . ' 00:00:00') . "
            and fechacreacion_negocio_detalle <= " . ConsultaGlobal::esc($fecha_fin . ' 23:59:59') . "
            " . ScopeUsuario::filtroBodega('negocio.id_bodega') . "
            GROUP BY id_producto
            ORDER BY total_negocio_detalle desc";
        $datos = (new ConsultaGlobal())->ConsultaGlobal($query);

        // Paleta del sistema (azul institucional #1976D2).
        $AZUL = '1976D2';
        $AZUL_CLARO = 'E3F2FD';
        $GRIS = 'F5F7FA';
        $BORDE = '000000';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Venta de Producto');

        // --- Título ---
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'REPORTE DE VENTA DE PRODUCTO');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB($AZUL);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(26);

        // --- Subtítulo (rango de fechas) ---
        $sheet->mergeCells('A2:C2');
        $sheet->setCellValue('A2', 'Desde: ' . $fecha_inicio . '     Hasta: ' . $fecha_fin);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->getColor()->setRGB('8D97AD');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // --- Cabecera de tabla (fila 4) ---
        $cab = 4;
        $sheet->setCellValue('A' . $cab, 'PRODUCTO');
        $sheet->setCellValue('B' . $cab, 'CANTIDAD');
        $sheet->setCellValue('C' . $cab, 'TOTAL (S/)');
        $estiloCab = $sheet->getStyle('A' . $cab . ':C' . $cab);
        $estiloCab->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $estiloCab->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($AZUL);
        $estiloCab->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($cab)->setRowHeight(22);

        // --- Datos (zebra) ---
        $fila = $cab + 1;
        $primeraData = $fila;
        $totalGeneral = 0;
        $idx = 0;
        foreach ($datos as $d) {
            $sheet->setCellValue('A' . $fila, $d->glosa_producto);
            $sheet->setCellValue('B' . $fila, (float) $d->cantidad_negocio_detalle);
            $sheet->setCellValue('C' . $fila, (float) $d->total_negocio_detalle);
            if ($idx % 2 == 1) {
                $sheet->getStyle('A' . $fila . ':C' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($GRIS);
            }
            $totalGeneral += (float) $d->total_negocio_detalle;
            $fila++;
            $idx++;
        }
        $ultimaData = $fila - 1;

        // --- Fila TOTAL ---
        $sheet->setCellValue('A' . $fila, 'TOTAL');
        $sheet->mergeCells('A' . $fila . ':B' . $fila);
        $sheet->setCellValue('C' . $fila, round($totalGeneral, 2));
        $estiloTotal = $sheet->getStyle('A' . $fila . ':C' . $fila);
        $estiloTotal->getFont()->setBold(true);
        $estiloTotal->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($AZUL_CLARO);

        // --- Bordes en toda la tabla ---
        $sheet->getStyle('A' . $cab . ':C' . $fila)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB($BORDE);

        // --- Formato moneda (col C) y centrado de cantidad (col B) ---
        $sheet->getStyle('C' . $primeraData . ':C' . $fila)->getNumberFormat()->setFormatCode('"S/ "#,##0.00');
        if ($ultimaData >= $primeraData) {
            $sheet->getStyle('B' . $primeraData . ':B' . $ultimaData)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getColumnDimension('A')->setWidth(45);
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->freezePane('A5');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $excelFileContent = ob_get_clean();

        $fechacreacion = date('Y-m-d');
        $fileName = "Venta_Producto$fechacreacion.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        echo $excelFileContent;
    }
}
