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

class ProductoExcelController
{
    public function EnviarArchivoProducto()
    {
        $Excel = $_FILES['archivo'];
        $guardado = $_FILES['archivo']['tmp_name'];
        $nombreArchivo =  $Excel['name'];
        $nombreArchivos = pathinfo($nombreArchivo, PATHINFO_FILENAME);
        $path = "archivo/ImportarExcelProducto";
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



                    $codigo_producto_insertar = null;
                    if (isset($elemento[0])) {
                        if (!empty($elemento[0])) {
                            $codigo_producto_insertar = trim($elemento[0]);
                        }
                    }

                    $nombre_producto = null;
                    if (!empty($elemento[1])) {
                        $nombre_producto = $elemento[1];
                    }


                    $tipo_producto = null;
                    if (isset($elemento[2])) {
                        if (!empty($elemento[2])) {
                            $tipo_producto =  $elemento[2];
                        }
                    }
                    $tipo_inventario = null;
                    if (isset($elemento[3])) {
                        if (!empty($elemento[3])) {
                            $tipo_inventario =  $elemento[3];
                        }
                    }




                    $cantidad_producto = null;
                    if ($elemento[12] !== "") {
                        $cantidad_producto =  $elemento[12];
                    } else if ($elemento[12] === 0) {
                        $cantidad_producto = 0;
                    }

                    $precio_venta = null;
                    if ($elemento[13] !== "") {
                        $precio_venta = $elemento[13];
                    } else if ($elemento[13] === 0) {
                        $precio_venta = 0;
                    }
                    if (
                        $tipo_producto === null || $tipo_inventario === null || $nombre_producto === null  || $cantidad_producto === null || $precio_venta === null
                    ) {
                        $fila = $key;
                        $datosnull = array(
                            "Tipo Producto" => $tipo_producto,
                            "Tipo Inventario" => $tipo_inventario,
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
                        $codigo_producto_insertar = null;
                        if (isset($elemento[0])) {
                            if (!empty($elemento[0])) {
                                $codigo_producto_insertar = trim($elemento[0]);
                            }
                        }
                        $nombre_producto_insertar = null;
                        if (isset($elemento[1])) {
                            if (!empty($elemento[1])) {
                                $nombre_producto_insertar = trim($elemento[1]);
                                $nombre_producto_insertar = str_replace("  ", " ", $nombre_producto_insertar);
                            }
                        }

                        $tipo_producto_insertar = null;
                        if (isset($elemento[2])) {
                            if (!empty($elemento[2])) {
                                $tipo_producto_insertar =  trim($elemento[2]);
                            }
                        }
                        $tipo_inventario_insertar = null;
                        if (isset($elemento[3])) {
                            if (!empty($elemento[3])) {
                                $tipo_inventario_insertar = trim($elemento[3]);
                                $tipo_inventario_insertar = str_replace("  ", " ", $tipo_inventario_insertar);
                            }
                        }
                        $unidad_insertar = null;
                        if (isset($elemento[4])) {
                            if (!empty($elemento[4])) {
                                $unidad_insertar = trim($elemento[4]);
                            }
                        }
                        $tipo_concentracion_insertar = null;
                        if (isset($elemento[5])) {
                            if (!empty($elemento[5])) {
                                $tipo_concentracion_insertar = trim($elemento[5]);
                            }
                        }

                        $marca_insertar = null;
                        if (isset($elemento[6])) {
                            if (!empty($elemento[6])) {
                                $marca_insertar =  trim($elemento[6]);
                                $marca_insertar = str_replace("  ", " ", $marca_insertar);
                            }
                        }
                        $nombre_proveedor_insertar = null;
                        if (isset($elemento[7])) {
                            if (!empty($elemento[7])) {
                                $nombre_proveedor_insertar =  trim($elemento[7]);
                                $nombre_proveedor_insertar = str_replace("  ", " ", $nombre_proveedor_insertar);
                            }
                        }
                        $visibleonline_producto = null;
                        if (isset($elemento[8])) {
                            if (!empty($elemento[8])) {
                                $visibleonline_producto =  trim($elemento[8]);
                                $visibleonline_producto = $visibleonline_producto === "SI" ? 1 : 0;
                            }
                        }



                        $multidosis_insertar = null;
                        if (isset($elemento[9])) {
                            if (!empty($elemento[9])) {
                                $multidosis_insertar =  trim($elemento[9]);
                            }
                        }
                        $dosis_insertar = null;
                        if (isset($elemento[10])) {
                            if (!empty($elemento[10])) {
                                $dosis_insertar =  trim($elemento[10]);
                            }
                        }

                        $concentracion_insertar = null;
                        if (isset($elemento[11])) {
                            if (!empty($elemento[11])) {
                                $concentracion_insertar =  trim($elemento[11]);
                            }
                        }
                        $cantidad_producto_insertar = null;
                        if (isset($elemento[12])) {
                            $cantidad_producto_insertar = trim($elemento[12]);
                        }
                        $precio_venta_insertar = null;
                        if (isset($elemento[13])) {
                            if (!empty($elemento[13])) {
                                $precio_venta_insertar = trim($elemento[13]);
                            }
                        }
                        $precio_costo_insertar = null;
                        if (isset($elemento[14])) {
                            if (!empty($elemento[14])) {
                                $precio_costo_insertar = trim($elemento[14]);
                            }
                        }
                        $categoria = null;
                        if (isset($elemento[15])) {
                            if (!empty($elemento[15])) {
                                // Eliminar espacios adicionales
                                $categoria = preg_replace('/\s+/', ' ', $elemento[15]);
                                $categoria = trim($categoria);
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
                                $id_tipo_concentracion = null;
                                if ($unidad_insertar && $tipo_concentracion_insertar) {
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


                                $urlAmigable_producto = "";
                                $urlAmigable_producto .= str_replace(" ", "-",  $nombre_producto_insertar);
                                $urlAmigable_producto = str_replace("/", "-", $urlAmigable_producto);
                                $urlAmigable_producto = str_replace("\\", "-", $urlAmigable_producto);
                                $urlAmigable_producto = str_replace("+", "-", $urlAmigable_producto);
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
                                    "fechacreacion_producto" => date('Y-m-d H:i:s'),
                                    'urlamigable_producto' => $urlAmigable_producto
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

                                if ($categoria) {
                                    $Categorias = explode(',', $categoria);
                                    $id_categoria_padre = 0;
                                    foreach ($Categorias as $key => $element) {
                                        $PadreCategoria = Categorias::where('glosa_categoria', 'LIKE', "%$element%")
                                            ->where('id_categoria_padre', $id_categoria_padre)->first();
                                        if (!isset($PadreCategoria)) {
                                            $urlAmigable = "";
                                            $urlAmigable .= str_replace(" ", "-",  $element);
                                            $urlAmigable = str_replace("/", "-", $urlAmigable);
                                            $urlAmigable = str_replace("\\", "-", $urlAmigable);
                                            $urlAmigable = str_replace("+", "-", $urlAmigable);
                                            $fillable = [
                                                'id_tipo_inventario' => $id_tipo_inventario,
                                                'glosa_categoria' => $element,
                                                'id_categoria_padre' => $id_categoria_padre,
                                                'vigente_categoria' => 1,
                                                'visibleonline_categoria' => 0,
                                                'urlamigable_categoria' => $urlAmigable
                                            ];
                                            $categorias = Categorias::create($fillable);
                                            $id_categoria_padre = $categorias->id_categoria;
                                        } else {
                                            $id_categoria_padre = $PadreCategoria->id_categoria;
                                        }
                                    }
                                    $datos = [
                                        'id_categoria' => $id_categoria_padre,
                                        'id_producto' => $Producto->id_producto
                                    ];
                                    CategoriaProducto::create($datos);
                                }
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

                                if ($categoria) {
                                    $Categorias = explode(',', $categoria);
                                    $id_categoria_padre = 0;
                                    foreach ($Categorias as $key => $element) {
                                        $PadreCategoria = Categorias::where('glosa_categoria', 'LIKE', "%$element%")
                                            ->where('id_categoria_padre', $id_categoria_padre)->first();
                                        if (!isset($PadreCategoria)) {
                                            $urlAmigable = "";
                                            $urlAmigable .= str_replace(" ", "-",  $element);
                                            $urlAmigable = str_replace("/", "-", $urlAmigable);
                                            $urlAmigable = str_replace("\\", "-", $urlAmigable);
                                            $urlAmigable = str_replace("+", "-", $urlAmigable);
                                            $fillable = [
                                                'id_tipo_inventario' => $id_tipo_inventario,
                                                'glosa_categoria' => $element,
                                                'id_categoria_padre' => $id_categoria_padre,
                                                'vigente_categoria' => 1,
                                                'visibleonline_categoria' => 0,
                                                'urlamigable_categoria' => $urlAmigable
                                            ];
                                            $categorias = Categorias::create($fillable);
                                            $id_categoria_padre = $categorias->id_categoria;
                                        } else {
                                            $id_categoria_padre = $PadreCategoria->id_categoria;
                                        }
                                    }
                                    $datos = [
                                        'id_categoria' => $id_categoria_padre,
                                        'id_producto' => $Traendo_productos->id_producto
                                    ];
                                    CategoriaProducto::create($datos);
                                }
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
                echo "echo <br>" . $respuesta;
                exit();
            }
        }

        $retornando = [
            "respuesta" => $respuesta,
            "respuesta_producto_registrado" => $ProductoNoRegistrado,
            "validandoExcel" => $validandoExcel,
            "datosexistente" => $datosexistente
        ];
        unlink('archivo/ImportarExcelProducto/' . $nombre_excel);
        echo  json_encode($retornando);
    }

    public function exportarDatos()
    {

        $encabezado = [
            "Codigo del Producto  (Obligatorio)", "Nombre del Producto  (Obligatorio)", "Tipo Producto (Obligatorio)", "Tipo Inventario (Obligatorio)", "Unidad", "Tipo Concentración",
            "Marca", "Nombre del Laboratorio", 'Visible Online', "Multidosis", "Dosis", "Concentración", "Cantidad (Obligatorio)", "Precio Venta (Obligatorio)", "Precio Costo", "Categoria (separado con '|')"
        ];

        $consulta = "SELECT *,
        (SELECT GROUP_CONCAT(id_categoria)
            from categoria_producto where id_producto=producto.id_producto
            ) as categoria_producto
        FROM producto
        INNER JOIN tipo_producto using (id_tipo_producto)
        LEFT JOIN unidad using (id_unidad)
        LEFT JOIN marca using (id_marca)
        LEFT JOIN proveedor using (id_proveedor)
        LEFT JOIN tipo_concentracion using (id_tipo_concentracion)
        LEFT JOIN tipo_inventario using (id_tipo_inventario)
        where vigente_producto=1 ";
        $todos = (new ConsultaGlobal())->ConsultaGlobal($consulta);
        // GUARDAMOS EL ARCHIVO  //TRAEMOS EL EXCEL DE LA CRECION DE PYTHON
        //PYTHON EXPORTANTO ARCHIVO
        // $jsonarray = array();
        // foreach ($todos as  $elemento) {
        //     // CATEGORIA---------------------------------------------
        //     $texto = $elemento->categoria_producto;
        //     $categoriapartes = explode(",", $texto);
        //     $mostrarcategorias = '';
        //     foreach ($categoriapartes as  $id_categoria) {
        //         $idcategoriasub = $id_categoria;
        //         $respuestahijo = true;
        //         $categoriasnombres = '';
        //         while ($respuestahijo) {
        //             $categorias = Categorias::where('id_categoria', $idcategoriasub)
        //                 ->where('id_categoria_padre', '!=', 0)->first();
        //             if (isset($categorias)) {
        //                 $categoriasnombres .= $categorias->glosa_categoria . ',';
        //                 $idcategoriasub = $categorias->id_categoria_padre;
        //             } else {
        //                 $categorias = Categorias::where('id_categoria', $idcategoriasub)->first();
        //                 if (isset($categorias)) {
        //                     $categoriasnombres .= $categorias->glosa_categoria;
        //                 }
        //                 $respuestahijo = false;
        //             }
        //         }
        //         // Paso 1: Divide el string en un array separado por comas
        //         $array = explode(",", $categoriasnombres);
        //         // Paso 3: Invierte el orden del array
        //         $array = array_reverse($array);
        //         // Paso 4: Convierte el array nuevamente en un string separado por comas
        //         $result = implode(",", $array);
        //         $mostrarcategorias .= $result . '|';
        //     }
        //     $mostrarcategorias = rtrim($mostrarcategorias, '|');
        //     $jsonarray[] = array(
        //         $encabezado[0] => $elemento->codigo_producto,
        //         $encabezado[1] => $elemento->glosa_producto,
        //         $encabezado[2] => $elemento->glosa_tipo_producto,
        //         $encabezado[3] => $elemento->glosa_tipo_inventario,
        //         $encabezado[4] => $elemento->glosa_unidad,
        //         $encabezado[5] => $elemento->glosa_tipo_concentracion,
        //         $encabezado[6] => $elemento->glosa_marca,
        //         $encabezado[7] => $elemento->glosa_proveedor,
        //         $encabezado[8] => ($elemento->visibleonline_producto == 1)  ? 'SI' : 'NO',
        //         $encabezado[9] => ($elemento->multidosis_producto == 'null')  ? null : $elemento->multidosis_producto,
        //         $encabezado[10] => ($elemento->dosis_producto),
        //         $encabezado[11] => ($elemento->concentracion_producto),
        //         $encabezado[12] => ($elemento->stock_producto),
        //         $encabezado[13] => ($elemento->precioventa_producto),
        //         $encabezado[14] => ($elemento->preciocosto_producto),
        //         $encabezado[15] => ($mostrarcategorias)
        //     );
        // }
        // $jsonarray = json_encode($jsonarray);
        // $file = fopen('datos.txt', 'w');
        // fwrite($file, $jsonarray);
        // fclose($file);
        // $comando = __DIR__ . '/../Helpers/exportar.py';
        // $respuesta = exec($comando);
        // if (empty($respuesta)) {
        //     echo "Error" . $respuesta;
        //     exit;
        // }

        // $inputFileName = __DIR__ . '/../datos.xlsx';
        // $spreadsheet = IOFactory::load($inputFileName);
        // // Obtener el contenido del archivo Excel como una cadena de bytes y lo creamos en datos.txt
        // $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        // ob_start();
        // $writer->save('php://output');
        // $excelFileContent = ob_get_clean();
        // //ELIMINAMOS LOS ARCHIVOS CREADOS
        // unlink($inputFileName);
        // unlink(__DIR__ . '/../datos.txt');
        // // ------------------------------------
        // // Enviar la respuesta HTTP para descargar el archivo Excel
        // $fechacreacion = date('Y-m-d');
        // $fileName = "Inventario_excel$fechacreacion.xlsx";
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        // echo $excelFileContent;
        // exit;
        //-----------------------------------------------------------

        $fechacreacion = date('Y-m-d');
        $spread = new Spreadsheet();
        $sheet = $spread->getActiveSheet();
        $gdImage = imagecreatefrompng(RUTA_ARCHIVO . "/archivo/imagenes/ahorro_farma.png");
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
        $celda="A1:P1";
        $sheet->getStyle($celda)->applyFromArray([
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
        $this->cellColor('A2:P2', 'DFE2E1', $sheet);
        $this->cellColor('A2:D2', 'D8EA39', $sheet);
        $this->cellColor('M2:N2', 'D8EA39', $sheet);
        // $this->cellColor('E2:K2', 'DFE2E1', $sheet);

        # El último argumento es por defecto A1
        $sheet->fromArray($encabezado, null, 'A2');
        $sheet->fromArray(["INVENTARIO DEL PRODUCTO"], null, 'A1');

        //SE UNE LAS CELDAS
        $sheet->mergeCells($celda);
        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }


        // TRAEMOS TODOS LOS TIPO DE INVENTARIO
        $tipo_inventario = TipoInventario::where('vigente_tipo_inventario', 1)->get()->toArray();
        $TipoProducto = TipoProductos::where('vigente_tipo_producto', 1)->get()->toArray();
        $unidad = Unidad::where('vigente_unidad', 1)->get()->toArray();
        $marca = Marca::where('vigente_marca', 1)->get()->toArray();
        $TipoConcentracion = TipoConcentracion::where('vigente_tipo_concentracion', 1)->get()->toArray();
        foreach ($todos as $key => $elemento) {
            $indice = $key + 3;
            $sheet->setCellValue("A$indice", $elemento->codigo_producto);
            $sheet->setCellValue("B$indice", $elemento->glosa_producto);
            $sheet->setCellValue("C$indice", $elemento->glosa_tipo_producto);
            $sheet->setCellValue("D$indice", $elemento->glosa_tipo_inventario);
            $sheet->setCellValue("E$indice", $elemento->glosa_unidad);
            $sheet->setCellValue("F$indice", $elemento->glosa_tipo_concentracion);
            $sheet->setCellValue("G$indice", $elemento->glosa_marca);
            $sheet->setCellValue("H$indice", $elemento->glosa_proveedor);

            $sheet->setCellValue("I$indice", ($elemento->visibleonline_producto == 1)  ? 'SI' : 'NO');


            $sheet->setCellValue("J$indice", ($elemento->multidosis_producto == 'null')  ? null : $elemento->multidosis_producto);
            $sheet->setCellValue("K$indice", $elemento->dosis_producto);
            $sheet->setCellValue("L$indice", $elemento->concentracion_producto);
            $sheet->setCellValue("M$indice", $elemento->stock_producto);
            $sheet->setCellValue("N$indice", $elemento->precioventa_producto);
            $sheet->setCellValue("O$indice", $elemento->preciocosto_producto);
            // CATEGORIA---------------------------------------------
            $texto = $elemento->categoria_producto;
            $categoria_partes = explode(",", $texto);
            $mostar_categorias = '';
            foreach ($categoria_partes as $key => $id_categoria) {
                $id_categoria_sub = $id_categoria;
                $respuesta_hijo = true;
                $categorias_nombres = '';
                while ($respuesta_hijo) {
                    $Categorias = Categorias::where('id_categoria', $id_categoria_sub)
                        ->where('id_categoria_padre', '!=', 0)->first();
                    if (isset($Categorias)) {
                        $categorias_nombres .= $Categorias->glosa_categoria . ',';
                        $id_categoria_sub = $Categorias->id_categoria_padre;
                    } else {
                        $Categorias = Categorias::where('id_categoria', $id_categoria_sub)->first();
                        if (isset($categorias)) {
                            $categorias_nombres .= $Categorias->glosa_categoria;
                        }
                        $respuesta_hijo = false;
                    }
                }

                // Paso 1: Divide el string en un array separado por comas
                $array = explode(",", $categorias_nombres);
                // Paso 3: Invierte el orden del array
                $array = array_reverse($array);
                // Paso 4: Convierte el array nuevamente en un string separado por comas
                $result = implode(",", $array);
                $mostar_categorias .= $result . '|';
            }
            $mostar_categorias = rtrim($mostar_categorias, '|');
            $sheet->setCellValue("P$indice", $mostar_categorias);
            // ----------------------------------------------------------------
            //ASIGANMOS A CADA CELDA EL SELECT multiple
            // GLOSA TIPO PRODUCTO
            $validation = $sheet->getCell('C' . ($indice))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$C$1:$C$' . count($TipoProducto));
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
            //TIPO INVENTARIO
            if (count($tipo_inventario) > 0) {
                $validation = $sheet->getCell('D' . ($indice))->getDataValidation();
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
            // //ASIGANMOS A CADA CELDA EL SELECT multiple
            // //UNIDAD
            if (count($unidad) > 0) {
                $validation = $sheet->getCell('E' . ($indice))->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('=\'Segunda_Hoja\'!$E$1:$E$' . count($unidad));
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
            // //ASIGANMOS A CADA CELDA EL SELECT multiple
            // //TIPO CONCENTRACION
            if (count($tipo_inventario) > 0) {
                $validation = $sheet->getCell('F' . ($indice))->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('=\'Segunda_Hoja\'!$F$1:$F$' . count($tipo_inventario));
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
            // //ASIGANMOS A CADA CELDA EL SELECT multiple
            // //MARCA
            if (count($marca) > 0) {
                $validation = $sheet->getCell('G' . ($indice))->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('=\'Segunda_Hoja\'!$G$1:$G$' . count($marca));
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
            $objPHPExcel->setCellValue('C' . ($key + 1), $elemento['glosa_tipo_producto']);
        }
        foreach ($unidad as $key => $elemento) {
            $objPHPExcel->setCellValue('E' . ($key + 1), $elemento['glosa_unidad']);
        }
        foreach ($TipoConcentracion as $key => $elemento) {
            $objPHPExcel->setCellValue('F' . ($key + 1), $elemento['glosa_tipo_concentracion']);
        }

        foreach ($tipo_inventario as $key => $elemento) {
            $objPHPExcel->setCellValue('D' . ($key + 1), $elemento['glosa_tipo_inventario']);
        }

        foreach ($marca as $key => $elemento) {
            $objPHPExcel->setCellValue('G' . ($key + 1), $elemento['glosa_marca']);
        }
        $objPHPExcel->setTitle('Segunda_Hoja');
        $fileName = "Inventario_excel$fechacreacion.xlsx";
        # Crear un "escritor"
        $writer = new Xlsx($spread);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
    }
    public function ExportarPlantilla()
    {
        $celda="A1:P1";
        $fechacreacion = date('Y-m-d');
        $spread = new Spreadsheet();
        $sheet = $spread->getActiveSheet();
        $gdImage = imagecreatefrompng(RUTA_ARCHIVO . "/archivo/imagenes/ahorro_farma.png");
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
        $sheet->getStyle($celda)->applyFromArray([
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
        $this->cellColor('A2:P2', 'DFE2E1', $sheet);
        $this->cellColor('A2:D2', 'D8EA39', $sheet);
        $this->cellColor('M2:N2', 'D8EA39', $sheet);
        // $this->cellColor('E2:K2', 'DFE2E1', $sheet);

        $encabezado = [
            "Codigo del Producto  (Obligatorio)", "Nombre del Producto  (Obligatorio)", "Tipo Producto (Obligatorio)", "Tipo Inventario (Obligatorio)", "Unidad", "Tipo Concentración ",
            "Marca", "Nombre del Laboratorio", 'Visible Online', "Multidosis", "Dosis", "Concentración", "Cantidad (Obligatorio)", "Precio Venta (Obligatorio)", "Precio Costo", "Categoria (Separar por ',')"
        ];
        # El último argumento es por defecto A1
        $sheet->fromArray($encabezado, null, 'A2');
        $sheet->fromArray(["INVENTARIO DEL PRODUCTO"], null, 'A1');

        //SE UNE LAS CELDAS
        $sheet->mergeCells($celda);
        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        // TRAEMOS TODOS LOS TIPO DE INVENTARIO
        $tipo_inventario = TipoInventario::where('vigente_tipo_inventario', 1)->get()->toArray();
        $TipoProducto = TipoProductos::where('vigente_tipo_producto', 1)->get()->toArray();
        $unidad = Unidad::where('vigente_unidad', 1)->get()->toArray();
        $marca = Marca::where('vigente_marca', 1)->get()->toArray();
        $TipoConcentracion = TipoConcentracion::where('vigente_tipo_concentracion', 1)->get()->toArray();
        $indice = 3;
        //ASIGANMOS A CADA CELDA EL SELECT multiple
        // GLOSA TIPO PRODUCTO
        $validation = $sheet->getCell('C' . ($indice))->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setFormula1('=\'Segunda_Hoja\'!$C$1:$C$' . count($TipoProducto));
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
        //TIPO INVENTARIO
        if (count($tipo_inventario) > 0) {
            $validation = $sheet->getCell('D' . ($indice))->getDataValidation();
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
        // //ASIGANMOS A CADA CELDA EL SELECT multiple
        // //UNIDAD
        if (count($unidad) > 0) {
            $validation = $sheet->getCell('E' . ($indice))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$E$1:$E$' . count($unidad));
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
        // //ASIGANMOS A CADA CELDA EL SELECT multiple
        // //TIPO CONCENTRACION
        if (count($tipo_inventario) > 0) {
            $validation = $sheet->getCell('F' . ($indice))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$F$1:$F$' . count($tipo_inventario));
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
        // //ASIGANMOS A CADA CELDA EL SELECT multiple
        // //MARCA
        if (count($marca) > 0) {
            $validation = $sheet->getCell('G' . ($indice))->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setFormula1('=\'Segunda_Hoja\'!$G$1:$G$' . count($marca));
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

        $sheet->setTitle("Productos Almacenado");
        //FINALIZAR LA PRIMERA HOJA DEL EXCEL
        //CREAMOS LA SEGUNDA HOJAS NO SE PONE EL ACTIVE PARA INICIARLZAR LA HOJA DE TRABAJO
        $objPHPExcel = $spread->createSheet();
        //OCULTAMOS LA SEGUNDA HOJA
        $objPHPExcel = $objPHPExcel->setSheetState(Worksheet::SHEETSTATE_HIDDEN);
        foreach ($TipoProducto as $key => $elemento) {
            //Otra manera de pintar de pinta la celda con setcellvalue
            $objPHPExcel->setCellValue('C' . ($key + 1), $elemento['glosa_tipo_producto']);
        }
        foreach ($unidad as $key => $elemento) {
            $objPHPExcel->setCellValue('E' . ($key + 1), $elemento['glosa_unidad']);
        }
        foreach ($TipoConcentracion as $key => $elemento) {
            $objPHPExcel->setCellValue('F' . ($key + 1), $elemento['glosa_tipo_concentracion']);
        }

        foreach ($tipo_inventario as $key => $elemento) {
            $objPHPExcel->setCellValue('D' . ($key + 1), $elemento['glosa_tipo_inventario']);
        }

        foreach ($marca as $key => $elemento) {
            $objPHPExcel->setCellValue('G' . ($key + 1), $elemento['glosa_marca']);
        }
        $objPHPExcel->setTitle('Segunda_Hoja');
        $fileName = "Plantilla_excel$fechacreacion.xlsx";
        # Crear un "escritor"
        $writer = new Xlsx($spread);
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
