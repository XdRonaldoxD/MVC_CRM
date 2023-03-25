<?php

use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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



class ProductoExcelController
{

    public function EnviarArchivoProducto()
    {

        $Excel = $_FILES['archivo'];
        $guardado = $_FILES['archivo']['tmp_name'];
        $nombreArchivo =  $Excel['name'];
        $nombreArchivos = pathinfo($nombreArchivo, PATHINFO_FILENAME);
        $path = "archivos/ImportarExcelProducto";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $nombre_excel = $nombreArchivos . time() . '.' .  pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        move_uploaded_file($guardado, $path . '/' . $nombre_excel);
        $ruta = $path . "/" . $nombre_excel;
        $documento = IOFactory::load($ruta);
        // print_r($cantidad);
        $MascotaExiste = array();
        $respuesta = "";
        $ProductoNoRegistrado = array();
        $validandoExcel = array();
        $datosexistente = array();
        $validarExcel = false;
        //saber la cantidad de hojas
        $totalDeHojas  =  $documento->getSheetCount();
        // for ($i = 0; $i < $totalDeHojas; $i++) {
        $hojaActual = $documento->getSheet(0);
        # Iterar filas
        try {
            foreach ($hojaActual->getRowIterator() as $key => $fila) {
                if ($key >= 3) {
                    $elemento = array();
                    foreach ($fila->getCellIterator() as $i => $celda) {
                        # El valor, así como está en el documento
                        $valorRaw = trim($celda->getValue());
                        array_push($elemento, $valorRaw);
                    }
                    $tipo_producto = null;
                    if (isset($elemento[0])) {
                        if (!empty($elemento[0])) {
                            $tipo_producto =  $elemento[0];
                        }
                    }

                    $unidad = null;
                    if (isset($elemento[1])) {
                        if (!empty($elemento[1])) {
                            $unidad =  $elemento[1];
                        }
                    }
                    $tipo_concentracion = null;
                    if (isset($elemento[2])) {
                        if (!empty($elemento[2])) {
                            $tipo_concentracion =  $elemento[2];
                        }
                    }

                    $tipo_inventario = null;
                    if (isset($elemento[3])) {
                        if (!empty($elemento[3])) {
                            $tipo_inventario =  $elemento[3];
                        }
                    }
                    $cantidad_producto = null;
                    if ($elemento[11] !== "") {
                        $cantidad_producto =  $elemento[11];
                    } else if ($elemento[11] === 0) {
                        $cantidad_producto = 0;
                    }
                    $nombre_producto = null;
                    if (!empty($elemento[6])) {
                        $nombre_producto = $elemento[6];
                    }
                    $codigo_producto_insertar = null;
                    if (isset($elemento[7])) {
                        if (!empty($elemento[7])) {
                            $codigo_producto_insertar = trim($elemento[7]);
                        }
                    }
                    $precio_venta = null;
                    if ($elemento[12] !== "") {
                        $precio_venta = $elemento[12];
                    } else if ($elemento[12] === 0) {
                        $precio_venta = 0;
                    }
                    if (
                        $tipo_producto === null || $tipo_concentracion === null || $tipo_inventario === null || $unidad === null
                        || $nombre_producto === null  || $cantidad_producto === null || $precio_venta === null
                    ) {
                        $fila = $key;
                        $datosnull = array(
                            "Tipo Producto" => $tipo_producto,
                            "Tipo Concentración" => $tipo_concentracion,
                            "Tipo Inventario" => $tipo_inventario,
                            "Unidad" => $unidad,
                            "Nombre Producto" => $nombre_producto,
                            "Cantidad Producto" => $cantidad_producto,
                            "Precio Venta" => $precio_venta
                        );
                        $columnas = "";
                        foreach ($datosnull as $key => $element) {
                            if ($element === null) {
                                $columnas .= $key . ',';
                            }
                        }
                        $columnas = substr($columnas, 0, -1);

                        //arregloColumna
                        $arreglocolumna = array(
                            "fila" => "Fila del Excel " . $fila,
                            "columna" => "$columnas~$nombre_producto~$nombre_producto~$codigo_producto_insertar",
                            "comentario" => "Campo vacio"
                        );
                        array_push($validandoExcel, $arreglocolumna);
                        $validarExcel = true;
                    }
                }
            }
        } catch (\Throwable $e) {
            $respuesta = $e->getMessage();
        }

        if ($validarExcel !== true) {
            try {
                foreach ($hojaActual->getRowIterator() as $key => $fila) {
                    if ($key >= 3) {
                        $elemento = array();
                        //Obtenemos los datos de la fila y lo guardamos de el arreglo
                        foreach ($fila->getCellIterator() as $i => $celda) {
                            # El valor, así como está en el documento
                            $valorRaw = trim($celda->getValue());
                            array_push($elemento, $valorRaw);
                        }
                        $tipo_producto_insertar = null;
                        if (isset($elemento[0])) {
                            if (!empty($elemento[0])) {
                                $tipo_producto_insertar =  trim($elemento[0]);
                            }
                        }
                        $unidad_insertar = null;
                        if (isset($elemento[1])) {
                            if (!empty($elemento[1])) {
                                $unidad_insertar = trim($elemento[1]);
                            }
                        }
                        $tipo_concentracion_insertar = null;
                        if (isset($elemento[2])) {
                            if (!empty($elemento[2])) {
                                $tipo_concentracion_insertar = trim($elemento[2]);
                            }
                        }
                        $tipo_inventario_insertar = null;
                        if (isset($elemento[3])) {
                            if (!empty($elemento[3])) {
                                $tipo_inventario_insertar = trim($elemento[3]);
                            }
                        }
                        $marca_insertar = null;
                        if (isset($elemento[4])) {
                            if (!empty($elemento[4])) {
                                $marca_insertar =  trim($elemento[4]);
                            }
                        }
                        $nombre_proveedor_insertar = null;
                        if (isset($elemento[5])) {
                            if (!empty($elemento[5])) {
                                $nombre_proveedor_insertar =  trim($elemento[5]);
                            }
                        }
                        $nombre_producto_insertar = null;
                        if (isset($elemento[6])) {
                            if (!empty($elemento[6])) {
                                $nombre_producto_insertar = trim($elemento[6]);
                            }
                        }
                        $codigo_producto_insertar = null;
                        if (isset($elemento[7])) {
                            if (!empty($elemento[7])) {
                                $codigo_producto_insertar = trim($elemento[7]);
                            }
                        }
                        $multidosis_insertar = null;
                        if (isset($elemento[8])) {
                            if (!empty($elemento[8])) {
                                $multidosis_insertar =  trim($elemento[8]);
                            }
                        }
                        $dosis_insertar = null;
                        if (isset($elemento[9])) {
                            if (!empty($elemento[9])) {
                                $dosis_insertar =  trim($elemento[9]);
                            }
                        }

                        $concentracion_insertar = null;
                        if (isset($elemento[10])) {
                            if (!empty($elemento[10])) {
                                $concentracion_insertar =  trim($elemento[10]);
                            }
                        }

                        $cantidad_producto_insertar = null;
                        if (isset($elemento[11])) {
                            $cantidad_producto_insertar = trim($elemento[11]);
                        }
                        $precio_venta_insertar = null;
                        if (isset($elemento[12])) {
                            if (!empty($elemento[12])) {
                                $precio_venta_insertar = trim($elemento[12]);
                            }
                        }

                        $precio_costo_insertar = null;
                        if (isset($elemento[13])) {
                            if (!empty($elemento[13])) {
                                $precio_costo_insertar = trim($elemento[13]);
                            }
                        }
                        if ($_POST['tipo_accion'] == "CREAR") {
                            $Traendo_productos = Producto::where('codigo_producto', $codigo_producto_insertar)->first();
                            if (isset($Traendo_productos)) {
                                $elementos = [
                                    "fila" => "Fila del Excel " . $key,
                                    "columna" => "Nombre Producto,Codigo Producto~$nombre_producto_insertar~$codigo_producto_insertar",
                                    "comentario" => "Producto  $codigo_producto_insertar,existente Verificar."
                                ];
                                array_push($datosexistente, $elementos);
                            } else {
                                $tipoProducto = TipoProductos::where('glosa_tipo_producto', 'LIKE', "%$tipo_producto_insertar%")->first();
                                if (isset($tipoProducto)) {
                                    $id_tipo_producto = $tipoProducto['id_tipo_producto'];
                                } else {
                                    $id_tipo_producto = $tipo_producto->Registrar();
                                    TipoProductos::create([
                                        'glosa_tipo_producto' => $tipo_producto_insertar,
                                        'vigente_tipo_producto' => 1
                                    ]);
                                }
                                //CREA EL TIPO DE UNIDAD
                                $id_unidad = null;
                                $unidad = Unidad::where('glosa_unidad', 'like', "%$unidad_insertar%")->first();
                                if (isset($unidad)) {
                                    $id_unidad = $unidad['id_unidad'];
                                } else {
                                    $unidad = Unidad::create([
                                        'glosa_unidad' => $unidad_insertar,
                                        'vigente_unidad' => 1
                                    ]);
                                    $id_unidad = $unidad->id_unidad;
                                }
                                //SE VERIFICA EL TIPO DE CONCENTRACIÓN
                                $id_tipo_concentracion = null;
                                $TipoConcentracion = TipoConcentracion::where('glosa_tipo_concentracion', 'like', "%$tipo_concentracion_insertar%")->first();
                                if (isset($TipoConcentracion)) {
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                } else {
                                    $TipoConcentracion = TipoConcentracion::create([
                                        'id_unidad' => $id_unidad,
                                        'glosa_tipo_concentracion' => $tipo_concentracion_insertar,
                                        'vigente_tipo_concentracion' => 1,
                                    ]);
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                }
                                $id_tipo_inventario = null;
                                $TipoInventario = TipoInventario::where('glosa_tipo_inventario', 'like', "%$tipo_inventario_insertar%")->first();
                                if ($TipoInventario) {
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                } else {
                                    $TipoInventario = TipoInventario::create([
                                        'glosa_tipo_inventario' => $tipo_inventario_insertar,
                                        'vigente_tipo_inventario' => 1
                                    ]);
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                }

                                $id_marca = null;
                                $marca = Marca::where('glosa_marca', 'like', "%$marca_insertar%")->first();
                                if (isset($marca)) {
                                    $id_marca = $marca->id_marca;
                                } else {
                                    $marca = Marca::create([
                                        'glosa_marca' => $marca_insertar,
                                        'vigente_marca' => 1,
                                    ]);
                                    $id_marca = $marca->id_marca;
                                }
                                $id_proveedor = null;
                                $Proveedor = Proveedor::where('glosa_proveedor', 'like', "%$nombre_proveedor_insertar%")->first();
                                if ($Proveedor) {
                                    $id_proveedor = $Proveedor->id_proveedor;
                                } else {
                                    $Proveedor = Proveedor::create([
                                        'comentario_proveedor' => "Migrado desde Excel",
                                        'vigente_proveedor' => 1
                                    ]);
                                    $id_proveedor = $Proveedor->id_proveedor;
                                }

                                $elemento = [
                                    'id_tipo_producto' => $id_tipo_producto,
                                    'id_tipo_concentracion' => $id_tipo_concentracion,
                                    'id_tipo_inventario' => $id_tipo_inventario,
                                    'id_unidad' => $id_unidad,
                                    'id_marca' => $id_marca,
                                    'id_proveedor' => $id_proveedor,
                                    'codigo_producto' => $codigo_producto_insertar,
                                    'glosa_producto' => $nombre_producto_insertar,
                                    'multidosis_producto' => $multidosis_insertar,
                                    'dosis_producto' => $dosis_insertar,
                                    'concentracion_producto' => $concentracion_insertar,
                                    'cantidad_producto' => $cantidad_producto_insertar,
                                    'stock_producto' => $cantidad_producto_insertar,
                                    'precioventa_producto' => $precio_venta_insertar,
                                    'preciocosto_producto' => $precio_costo_insertar,
                                    'vigente_producto' => 1,
                                    "fechacreacion_producto" => date('Y-m-d H:i:s')
                                ];
                                $Producto = Producto::create($elemento);
                                $dataHistorial = [
                                    'id_usuario' => $_POST['id_usuario'],
                                    'id_tipo_movimiento' => 1,
                                    'id_producto' => $Producto->id_producto,
                                    'cantidadmovimiento_producto_historial' => $cantidad_producto_insertar,
                                    'fecha_producto_historial' => date('Y-m-d H:i:s'),
                                    'comentario_producto_historial' => "Migrado desde el excel.",
                                    'preciocompra_producto_historial' => $precio_costo_insertar
                                ];
                                ProductoHistorial::create($dataHistorial);
                            }
                        } else {
                            $Traendo_productos = Producto::where('codigo_producto', $codigo_producto_insertar)->first();
                            if (isset($Traendo_productos)) {
                                $tipoProducto = TipoProductos::where('glosa_tipo_producto', 'LIKE', "%$tipo_producto_insertar%")->first();
                                if (isset($tipoProducto)) {
                                    $id_tipo_producto = $tipoProducto['id_tipo_producto'];
                                } else {
                                    $id_tipo_producto = $tipo_producto->Registrar();
                                    TipoProductos::create([
                                        'glosa_tipo_producto' => $tipo_producto_insertar,
                                        'vigente_tipo_producto' => 1
                                    ]);
                                }
                                //CREA EL TIPO DE UNIDAD
                                $id_unidad = null;
                                $unidad = Unidad::where('glosa_unidad', 'like', "%$unidad_insertar%")->first();
                                if (isset($unidad)) {
                                    $id_unidad = $unidad['id_unidad'];
                                } else {
                                    $unidad = Unidad::create([
                                        'glosa_unidad' => $unidad_insertar,
                                        'vigente_unidad' => 1
                                    ]);
                                    $id_unidad = $unidad->id_unidad;
                                }
                                //SE VERIFICA EL TIPO DE CONCENTRACIÓN
                                $id_tipo_concentracion = null;
                                $TipoConcentracion = TipoConcentracion::where('glosa_tipo_concentracion', 'like', "%$tipo_concentracion_insertar%")->first();
                                if (isset($TipoConcentracion)) {
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                } else {
                                    $TipoConcentracion = TipoConcentracion::create([
                                        // 'id_unidad' => $id_unidad,
                                        'glosa_tipo_concentracion' => $tipo_concentracion_insertar,
                                        'vigente_tipo_concentracion' => 1,
                                    ]);
                                    $id_tipo_concentracion = $TipoConcentracion->id_tipo_concentracion;
                                }
                                $id_tipo_inventario = null;
                                $TipoInventario = TipoInventario::where('glosa_tipo_inventario', 'like', "%$tipo_inventario_insertar%")->first();
                                if ($TipoInventario) {
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                } else {
                                    $TipoInventario = TipoInventario::create([
                                        'glosa_tipo_inventario' => $tipo_inventario_insertar,
                                        'vigente_tipo_inventario' => 1
                                    ]);
                                    $id_tipo_inventario = $TipoInventario->id_tipo_inventario;
                                }

                                $id_marca = null;
                                $marca = Marca::where('glosa_marca', 'like', "%$marca_insertar%")->first();
                                if (isset($marca)) {
                                    $id_marca = $marca->id_marca;
                                } else {
                                    $marca = Marca::create([
                                        'glosa_marca' => $marca_insertar,
                                        'vigente_marca' => 1,
                                    ]);
                                    $id_marca = $marca->id_marca;
                                }
                                $id_proveedor = null;
                                $Proveedor = Proveedor::where('glosa_proveedor', 'like', "%$nombre_proveedor_insertar%")->first();
                                if ($Proveedor) {
                                    $id_proveedor = $Proveedor->id_proveedor;
                                } else {
                                    $Proveedor = Proveedor::create([
                                        'comentario_proveedor' => "Migrado desde Excel",
                                        'vigente_proveedor' => 1
                                    ]);
                                    $id_proveedor = $Proveedor->id_proveedor;
                                }

                                $elemento = [
                                    'id_tipo_producto' => $id_tipo_producto,
                                    'id_tipo_concentracion' => $id_tipo_concentracion,
                                    'id_tipo_inventario' => $id_tipo_inventario,
                                    'id_unidad' => $id_unidad,
                                    'id_marca' => $id_marca,
                                    'id_proveedor' => $id_proveedor,
                                    // 'codigo_producto' => $codigo_producto_insertar,
                                    'glosa_producto' => $nombre_producto_insertar,
                                    'multidosis_producto' => $multidosis_insertar,
                                    'dosis_producto' => $dosis_insertar,
                                    'concentracion_producto' => $concentracion_insertar,
                                    'cantidad_producto' => $cantidad_producto_insertar,
                                    'stock_producto' => $cantidad_producto_insertar,
                                    'precioventa_producto' => $precio_venta_insertar,
                                    'preciocosto_producto' => $precio_costo_insertar,
                                    'vigente_producto' => 1,
                                ];
                                Producto::where('id_producto', $Traendo_productos->id_producto)->update($elemento);
                                $dataHistorial = [
                                    'id_usuario' => $_POST['id_usuario'],
                                    'id_tipo_movimiento' => 3,
                                    'id_producto' => $Traendo_productos->id_producto,
                                    'cantidadmovimiento_producto_historial' => $cantidad_producto_insertar,
                                    'fecha_producto_historial' => date('Y-m-d H:i:s'),
                                    'comentario_producto_historial' => "Migrado desde el excel.",
                                    'preciocompra_producto_historial' => $precio_costo_insertar
                                ];
                                ProductoHistorial::create($dataHistorial);
                            } else {
                                $elementos = [
                                    "fila" => "Fila del Excel " . $key,
                                    "columna" => "Nombre Producto,Codigo Producto~$nombre_producto_insertar~$codigo_producto_insertar",
                                    "comentario" => "Producto  $codigo_producto_insertar,no existe Verificar."
                                ];
                                array_push($datosexistente, $elementos);
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                $respuesta = $e->getMessage();
            }
        }

        $retornando = [
            "respuesta" => $respuesta,
            "respuesta_producto_registrado" => $ProductoNoRegistrado,
            "validandoExcel" => $validandoExcel,
            "datosexistente" => $datosexistente
        ];
        unlink('archivos/ImportarExcelProducto/' . $nombre_excel);
        echo  json_encode($retornando);
    }

    public function ExportarDatos()
    {
        $fechacreacion = date('Y-m-d');
        $spread = new Spreadsheet();
        $sheet = $spread->getActiveSheet();
        $gdImage = imagecreatefrompng("http://localhost/MVC_APIVENTA//archivos/imagenes/ahorro_farma.png");
        // $textColor = imagecolorallocate($gdImage, 255, 255, 5);
        // imagestring($gdImage, 1, 7, 5, date("F Y"), $textColor);

        //  Add the In-Memory image to a worksheet
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing();
        $drawing->setName('In-Memory image 1');
        $drawing->setDescription('In-Memory image 1');
        $drawing->setCoordinates('A1');
        $drawing->setImageResource($gdImage);
        $drawing->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_JPEG);
        $drawing->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
        $drawing->setWidth(80);
        $drawing->setHeight(20);
        $drawing->setWorksheet($spread->getActiveSheet());
        $sheet->getStyle('A1:N1')->applyFromArray([
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
                    'rgb' => 'DFE2E1',
                ],
            ],
            'alignment' => [
                'horizontal' => 'center', // Centro horizontal
                'vertical' => 'center', // Centro vertical
            ],
        ]);
        $this->cellColor('A2:D2','D8EA39',$sheet);
        $this->cellColor('L2:M2','D8EA39',$sheet);
        $this->cellColor('E2:K2','DFE2E1',$sheet);
        $this->cellColor('N2','DFE2E1',$sheet);
        $encabezado = ["Tipo Producto (Obligatorio)", "Unidad (Obligatorio)", "Tipo Concentración (Obligatorio)", "Tipo Inventario (Obligatorio)
      ", "Marca", "Nombre del Laboratorio", "Nombre del Producto", "Codigo del Producto", "Multidosis", "Dosis", "Concentración
      ", "Cantidad (Obligatorio)", "Precio Venta (Obligatorio)", "Precio Costo"];
        # El último argumento es por defecto A1
        $sheet->fromArray($encabezado, null, 'A2');
        $sheet->fromArray(["INVENTARIO DEL PRODUCTO"], null, 'A1');

        //SE UNE LAS CELDAS
        $sheet->mergeCells('A1:N1');
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $todos = Producto::join('tipo_producto', 'tipo_producto.id_tipo_producto', 'producto.id_tipo_producto')
            ->leftjoin('unidad', 'unidad.id_unidad', 'producto.id_unidad')
            ->leftjoin('marca', 'marca.id_marca', 'producto.id_marca')
            ->leftjoin('proveedor', 'proveedor.id_proveedor', 'producto.id_proveedor')
            ->leftjoin('tipo_concentracion', 'tipo_concentracion.id_tipo_concentracion', 'producto.id_tipo_concentracion')
            ->leftjoin('tipo_inventario', 'tipo_inventario.id_tipo_inventario', 'producto.id_tipo_inventario')
            ->where('vigente_producto', 1)->get()->toArray();

        // TRAEMOS TODOS LOS TIPO DE INVENTARIO
        $tipo_inventario = TipoInventario::where('vigente_tipo_inventario', 1)->get()->toArray();
        $TipoProducto = TipoProductos::where('vigente_tipo_producto', 1)->get()->toArray();
        $unidad = Unidad::where('vigente_unidad', 1)->get()->toArray();
        $marca = Marca::where('vigente_marca', 1)->get()->toArray();
        $TipoConcentracion = TipoConcentracion::where('vigente_tipo_concentracion', 1)->get()->toArray();
        foreach ($todos as $key => $elemento) {
            $sheet->setCellValueByColumnAndRow(1, $key + 3, $elemento['glosa_tipo_producto']);
            $sheet->setCellValueByColumnAndRow(2, $key + 3, $elemento['glosa_unidad']);
            $sheet->setCellValueByColumnAndRow(3, $key + 3, $elemento['glosa_tipo_concentracion']);
            $sheet->setCellValueByColumnAndRow(4, $key + 3, $elemento['glosa_tipo_inventario']);
            $sheet->setCellValueByColumnAndRow(5, $key + 3, $elemento['glosa_marca']);
            $sheet->setCellValueByColumnAndRow(6, $key + 3, $elemento['glosa_proveedor']);
            $sheet->setCellValueByColumnAndRow(7, $key + 3, $elemento['glosa_producto']);
            $sheet->setCellValueByColumnAndRow(8, $key + 3, $elemento['codigo_producto']);
            $sheet->setCellValueByColumnAndRow(9, $key + 3, ($elemento['multidosis_producto'] == 'null')  ? null : $elemento['multidosis_producto']);
            $sheet->setCellValueByColumnAndRow(10, $key + 3, $elemento['dosis_producto']);
            $sheet->setCellValueByColumnAndRow(11, $key + 3, $elemento['concentracion_producto']);
            $sheet->setCellValueByColumnAndRow(12, $key + 3, $elemento['stock_producto']);
            $sheet->setCellValueByColumnAndRow(13, $key + 3, $elemento['precioventa_producto']);
            $sheet->setCellValueByColumnAndRow(14, $key + 3, $elemento['preciocosto_producto']);

            //ASIGANMOS A CADA CELDA EL SELECT multiple
            $validation = $sheet->getCell('A' . ($key + 3))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$A$1:$A$' . count($TipoProducto));
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Nota');
            $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
            $validation->setShowErrorMessage(false);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            // $validation->setErrorTitle('Opción Invalida.');
            // $validation->setError('Seleccione uno de la lista desplegable.');

            //ASIGANMOS A CADA CELDA EL SELECT multiple
            if (count($unidad) > 0) {
                $validation = $sheet->getCell('B' . ($key + 3))->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('=\'Segunda_Hoja\'!$B$1:$B$' . count($unidad));
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setShowInputMessage(true);
                $validation->setPromptTitle('Nota');
                $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
                $validation->setShowErrorMessage(false);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                // $validation->setErrorTitle('Opción Invalida.');
                // $validation->setError('Seleccione uno de la lista desplegable.');
            }

            if (count($unidad) > 0) {
                $validation = $sheet->getCell('C' . ($key + 3))->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('=\'Segunda_Hoja\'!$C$1:$C$' . count($TipoConcentracion));
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setShowInputMessage(true);
                $validation->setPromptTitle('Nota');
                $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
                $validation->setShowErrorMessage(false);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                // $validation->setErrorTitle('Opción Invalida.');
                // $validation->setError('Seleccione uno de la lista desplegable.');
            }

            if (count($tipo_inventario) > 0) {
                $validation = $sheet->getCell('D' . ($key + 3))->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('=\'Segunda_Hoja\'!$D$1:$D$' . count($tipo_inventario));
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setShowInputMessage(true);
                $validation->setPromptTitle('Nota');
                $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
                $validation->setShowErrorMessage(false);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                // $validation->setErrorTitle('Opción Invalida.');
                // $validation->setError('Seleccione uno de la lista desplegable.');
            }

            if (count($marca) > 0) {
                $validation = $sheet->getCell('E' . ($key + 3))->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('=\'Segunda_Hoja\'!$E$1:$E$' . count($marca));
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setShowInputMessage(true);
                $validation->setPromptTitle('Nota');
                $validation->setPrompt('Debe seleccionar uno de las opciones desplegables.');
                $validation->setShowErrorMessage(false);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                // $validation->setErrorTitle('Opción Invalida.');
                // $validation->setError('Seleccione uno de la lista desplegable.');
            }
        }
        $sheet->setTitle("Productos Almacenado");
        //FINALIZAR LA PRIMERA HOJA DEL EXCEL
        //CREAMOS LA SEGUNDA HOJAS NO SE PONE EL ACTIVE PARA INICIARLZAR LA HOJA DE TRABAJO
        $objPHPExcel = $spread->createSheet();
        //OCULTAMOS LA SEGUNDA HOJA
        $objPHPExcel = $objPHPExcel->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        foreach ($TipoProducto as $key => $elemento) {
            //Otra manera de pintar de pinta la celda con setcellvalue
            $objPHPExcel->setCellValue('A' . ($key + 1), $elemento['glosa_tipo_producto']);
        }
        foreach ($unidad as $key => $elemento) {
            $objPHPExcel->setCellValue('B' . ($key + 1), $elemento['glosa_unidad']);
        }
        foreach ($TipoConcentracion as $key => $elemento) {
            $objPHPExcel->setCellValue('C' . ($key + 1), $elemento['glosa_tipo_concentracion']);
        }
        
        foreach ($tipo_inventario as $key => $elemento) {
            $objPHPExcel->setCellValue('D' . ($key + 1), $elemento['glosa_tipo_inventario']);
        }

        foreach ($marca as $key => $elemento) {
            $objPHPExcel->setCellValue('E' . ($key + 1), $elemento['glosa_marca']);
        }
        $objPHPExcel->setTitle('Segunda_Hoja');
        $fileName = "Inventario_excel$fechacreacion.xlsx";
        # Crear un "escritor"
        $writer = new Xlsx($spread);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
    }

    public function cellColor($cells,$color,$sheet){
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
