<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once "models/ConsultaGlobal.php";

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
        $consulta = " and (glosa_producto LIKE '%$buscar%' or
        codigo_producto LIKE '%$buscar%' or codigo_barra_producto LIKE '%$buscar%' )";
        $query = "SELECT ROUND(SUM(total_negocio_detalle),2) as total_negocio_detalle,glosa_producto,SUM(cantidad_negocio_detalle) as cantidad_negocio_detalle
        FROM negocio_detalle
        inner join producto using (id_producto)
        inner join negocio using (id_negocio)
        WHERE vigente_negocio=1  and fechacreacion_negocio_detalle>= '$fecha_inicio 00:00:00' and fechacreacion_negocio_detalle<='$fecha_fin 23:59:59'
        $consulta
		GROUP BY id_producto
        ORDER BY id_negocio_detalle desc";

        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $datosPost->start ";
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
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $id_usuario = $_POST['id_usuario'];
        $segundacabecera = HOST . ';' . USERNAME . ';' . PASSWORD . ';' . BASE_DATOS . ';' . $fecha_inicio . ';' . $fecha_fin . ';' . $id_usuario;
        $segundacabecera = str_replace(' ', '', $segundacabecera);
        $segundacabecera = '"' . $segundacabecera . '"';//AGREGAMOS COMILLAS PARA LA CONCATENACION
        $comando = "python " . __DIR__ . "/../Helpers/python/exportarReporteProducto.py $segundacabecera";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') { //SABER SI TIENN HTTPS SITIOS DE PRUDUCCION
            $activateCommand = ACTIVAR_COMANDO_PYTHON;
            $respuesta = shell_exec($activateCommand . ' && ' . $comando);
        } else {
            $respuesta = shell_exec($comando);
        }
        if (empty($respuesta)) {
            echo "Error" . $respuesta;
            die(http_response_code(404));
        }
        $fecha_actual = date("Ymd");
        $path_excel="venta_producto_".$id_usuario."_".$fecha_actual.".xlsx";
        $inputFileName = __DIR__ . "/../$path_excel";
        $spreadsheet = IOFactory::load($inputFileName);
        // Obtener el contenido del archivo Excel como una cadena de bytes y lo creamos en datos.txt
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $excelFileContent = ob_get_clean();
        //ELIMINAMOS LOS ARCHIVOS CREADOS
        unlink($inputFileName);
        // ------------------------------------
        // Enviar la respuesta HTTP para descargar el archivo Excel
        $fechacreacion = date('Y-m-d');
        $fileName = "Venta_Producto$fechacreacion.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        echo $excelFileContent;
    }
}
