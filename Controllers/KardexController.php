<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

require_once "models/ConsultaGlobal.php";
require_once "Helpers/ScopeUsuario.php";

/**
 * [KARDEX] Reporte de movimientos de inventario por producto (kardex de unidades).
 * Se construye sobre `producto_historial` (1=Añadir/entrada, 2=Quitar/salida,
 * 3=Actualizar/ajuste). El saldo acumulado siempre cierra en el stock real porque
 * el saldo inicial se deriva de: stock_actual - Σ(movimientos firmados). Así, aunque
 * un producto no tenga su "entrada" inicial registrada, el kardex reconcilia.
 */
class KardexController
{
    /** Catálogo para los filtros: productos activos y bodegas. */
    public function filtros()
    {
        $cg = new ConsultaGlobal();
        $productos = $cg->ConsultaGlobal(
            "SELECT id_producto, glosa_producto, codigo_producto
             FROM producto WHERE vigente_producto=1 ORDER BY glosa_producto"
        );
        $bodegas = $cg->ConsultaGlobal(
            "SELECT id_bodega, glosa_bodega FROM bodega WHERE vigente_bodega=1" .
            ScopeUsuario::filtroBodega('id_bodega') . " ORDER BY glosa_bodega"
        );
        echo json_encode(['productos' => $productos, 'bodegas' => $bodegas]);
    }

    /** Genera el kardex (JSON) del producto/bodega/rango indicado. */
    public function generar()
    {
        $body = json_decode(file_get_contents("php://input"));
        $idProducto = isset($body->id_producto) ? (int) $body->id_producto : 0;
        $idBodega = isset($body->id_bodega) ? (int) $body->id_bodega : 0;
        $desde = !empty($body->fecha_desde) ? $body->fecha_desde . ' 00:00:00' : null;
        $hasta = !empty($body->fecha_hasta) ? $body->fecha_hasta . ' 23:59:59' : null;

        if ($idProducto <= 0) {
            http_response_code(400);
            echo json_encode("Debe seleccionar un producto.");
            return;
        }
        echo json_encode($this->construir($idProducto, $idBodega, $desde, $hasta));
    }

    /** Lógica central: arma el kardex con saldo acumulado reconciliado al stock real. */
    private function construir($idProducto, $idBodega, $desde, $hasta)
    {
        $cg = new ConsultaGlobal();

        $prod = $cg->ConsultaGlobal(
            "SELECT id_producto, glosa_producto, codigo_producto FROM producto WHERE id_producto=" . (int) $idProducto
        );
        $producto = isset($prod[0]) ? $prod[0] : null;

        // [SCOPE] Un no-admin solo ve su(s) bodega(s). Si pidió una bodega concreta
        // permitida, se usa; si no, se limita al conjunto permitido. Admin = todas.
        $scopeIds = ScopeUsuario::idsBodegas();
        if ($scopeIds !== null) {
            $bodIds = ($idBodega > 0 && in_array($idBodega, $scopeIds, true)) ? [$idBodega] : $scopeIds;
        } else {
            $bodIds = $idBodega > 0 ? [(int) $idBodega] : null;
        }
        $inList = $bodIds !== null ? '(' . implode(',', array_map('intval', $bodIds)) . ')' : null;

        $whereBodega = $inList !== null ? " AND id_bodega IN $inList" : "";
        $st = $cg->ConsultaGlobal(
            "SELECT COALESCE(SUM(total_stock_producto_bodega),0) AS stock
             FROM stock_producto_bodega WHERE id_producto=" . (int) $idProducto . $whereBodega
        );
        $stockActual = isset($st[0]) ? (float) $st[0]->stock : 0;

        $whereBodegaPh = $inList !== null ? " AND ph.id_bodega IN $inList" : "";
        $movs = $cg->ConsultaGlobal(
            "SELECT ph.id_producto_historial,
                ph.id_tipo_movimiento,
                ph.cantidadmovimiento_producto_historial AS cant,
                ph.fecha_producto_historial AS fecha,
                ph.comentario_producto_historial AS comentario,
                ph.numerotipodocumento_producto_historial AS num_doc,
                tm.glosa_tipo_movimiento AS tipo,
                td.glosa_tipo_documento AS tipo_doc,
                b.glosa_bodega AS bodega,
                TRIM(CONCAT(IFNULL(s.nombre_staff,''),' ',IFNULL(s.apellidopaterno_staff,''))) AS usuario
             FROM producto_historial ph
             LEFT JOIN tipo_movimiento tm ON tm.id_tipo_movimiento=ph.id_tipo_movimiento
             LEFT JOIN tipo_documento td ON td.id_tipo_documento=ph.id_tipo_documento
             LEFT JOIN bodega b ON b.id_bodega=ph.id_bodega
             LEFT JOIN usuario u ON u.id_usuario=ph.id_usuario
             LEFT JOIN staff s ON s.id_staff=u.id_staff
             WHERE ph.id_producto=" . (int) $idProducto . $whereBodegaPh . "
             ORDER BY ph.fecha_producto_historial ASC, ph.id_producto_historial ASC"
        );

        // Suma firmada de TODOS los movimientos (salida=2 resta; entrada/ajuste suman).
        $sumTotal = 0;
        foreach ($movs as $m) {
            $sumTotal += ((int) $m->id_tipo_movimiento === 2 ? -1 : 1) * (float) $m->cant;
        }
        // El saldo inicial global hace que el kardex cierre exactamente en el stock real.
        $saldo = $stockActual - $sumTotal;
        $saldoInicialRango = $saldo;

        $filas = [];
        $totEntrada = 0;
        $totSalida = 0;
        foreach ($movs as $m) {
            $esSalida = ((int) $m->id_tipo_movimiento === 2);
            $signed = ($esSalida ? -1 : 1) * (float) $m->cant;

            // Movimientos previos al rango: acumulan en el saldo inicial, no se listan.
            if ($desde !== null && $m->fecha < $desde) {
                $saldo += $signed;
                $saldoInicialRango = $saldo;
                continue;
            }
            // Movimientos posteriores al rango: fuera del kardex mostrado.
            if ($hasta !== null && $m->fecha > $hasta) {
                continue;
            }

            $saldo += $signed;
            $entrada = $esSalida ? 0 : (float) $m->cant;
            $salida = $esSalida ? (float) $m->cant : 0;
            $totEntrada += $entrada;
            $totSalida += $salida;

            $doc = trim((isset($m->tipo_doc) ? $m->tipo_doc : '') . ' ' . (isset($m->num_doc) ? $m->num_doc : ''));
            $filas[] = [
                'fecha' => $m->fecha,
                'tipo' => $m->tipo,
                'documento' => $doc,
                'bodega' => $m->bodega,
                'usuario' => $m->usuario,
                'comentario' => $m->comentario,
                'entrada' => $entrada,
                'salida' => $salida,
                'saldo' => $saldo,
            ];
        }

        return [
            'producto' => $producto,
            'saldo_inicial' => $saldoInicialRango,
            'saldo_final' => $saldo,
            'total_entrada' => $totEntrada,
            'total_salida' => $totSalida,
            'movimientos' => $filas,
        ];
    }

    /** Exporta el kardex a Excel (PhpSpreadsheet), mismo estilo que los demás reportes. */
    public function exportarKardex()
    {
        $idProducto = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;
        $idBodega = isset($_POST['id_bodega']) ? (int) $_POST['id_bodega'] : 0;
        $desde = !empty($_POST['fecha_desde']) ? $_POST['fecha_desde'] . ' 00:00:00' : null;
        $hasta = !empty($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] . ' 23:59:59' : null;

        $k = $this->construir($idProducto, $idBodega, $desde, $hasta);
        $producto = $k['producto'];
        $nombreProd = $producto ? $producto->glosa_producto : 'Producto';

        $AZUL = '1976D2';
        $AZUL_CLARO = 'E3F2FD';
        $GRIS = 'F5F7FA';
        $BORDE = '000000';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Kardex');

        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'KARDEX DE INVENTARIO (UNIDADES)');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB($AZUL);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(26);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Producto: ' . $nombreProd . ($producto ? '  |  SKU: ' . $producto->codigo_producto : ''));
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->mergeCells('A3:H3');
        $rango = 'Desde: ' . (!empty($_POST['fecha_desde']) ? $_POST['fecha_desde'] : 'inicio') .
            '     Hasta: ' . (!empty($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : 'hoy') .
            '     Saldo inicial: ' . $k['saldo_inicial'];
        $sheet->setCellValue('A3', $rango);
        $sheet->getStyle('A3')->getFont()->setItalic(true)->getColor()->setRGB('8D97AD');

        $cab = 5;
        $encabezado = ['FECHA', 'TIPO MOVIMIENTO', 'DOCUMENTO', 'BODEGA', 'USUARIO', 'ENTRADA', 'SALIDA', 'SALDO'];
        foreach ($encabezado as $i => $t) {
            $sheet->setCellValueByColumnAndRow($i + 1, $cab, $t);
        }
        $estiloCab = $sheet->getStyle('A' . $cab . ':H' . $cab);
        $estiloCab->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $estiloCab->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($AZUL);
        $estiloCab->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($cab)->setRowHeight(22);

        $fila = $cab + 1;
        $primera = $fila;
        $idx = 0;
        foreach ($k['movimientos'] as $m) {
            $sheet->setCellValueByColumnAndRow(1, $fila, date('d/m/Y H:i', strtotime($m['fecha'])));
            $sheet->setCellValueByColumnAndRow(2, $fila, $m['tipo']);
            $sheet->setCellValueByColumnAndRow(3, $fila, $m['documento']);
            $sheet->setCellValueByColumnAndRow(4, $fila, $m['bodega']);
            $sheet->setCellValueByColumnAndRow(5, $fila, $m['usuario']);
            $sheet->setCellValueByColumnAndRow(6, $fila, $m['entrada'] ?: '');
            $sheet->setCellValueByColumnAndRow(7, $fila, $m['salida'] ?: '');
            $sheet->setCellValueByColumnAndRow(8, $fila, $m['saldo']);
            if ($idx % 2 == 1) {
                $sheet->getStyle('A' . $fila . ':H' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($GRIS);
            }
            $fila++;
            $idx++;
        }
        $ultima = $fila - 1;

        // Fila de totales.
        $sheet->setCellValueByColumnAndRow(5, $fila, 'TOTALES');
        $sheet->setCellValueByColumnAndRow(6, $fila, $k['total_entrada']);
        $sheet->setCellValueByColumnAndRow(7, $fila, $k['total_salida']);
        $sheet->setCellValueByColumnAndRow(8, $fila, $k['saldo_final']);
        $sheet->getStyle('A' . $fila . ':H' . $fila)->getFont()->setBold(true);
        $sheet->getStyle('A' . $fila . ':H' . $fila)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($AZUL_CLARO);

        if ($ultima >= $primera) {
            $sheet->getStyle('A' . $cab . ':H' . $fila)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB($BORDE);
            $sheet->getStyle('F' . $primera . ':H' . $fila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->freezePane('A' . ($cab + 1));

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $contenido = ob_get_clean();

        $fileName = 'Kardex_' . preg_replace('/[^A-Za-z0-9]/', '_', $nombreProd) . '_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        echo $contenido;
    }
}
