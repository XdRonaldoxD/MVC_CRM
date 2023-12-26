<?php

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\TextUI\Help;

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
require_once "models/ProductoImagen.php";
require_once "models/Bodega.php";
require_once "models/StockProductoBodega.php";
require_once "config/Helper.php";

class ProductoExcelController
{
    public function EnviarArchivoProducto()
    {
        Cloudinary::config([
            'cloud_name' => cloud_name,
            'api_key'    => api_key,
            'api_secret' => api_secret,
            "secure" => true
        ]);
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
        $respuesta = "";
        $ProductoNoRegistrado = array();
        $validandoExcel = array();

        //saber la cantidad de hojas
        $hojaActual = $documento->getSheet(0);
        try {
            foreach ($hojaActual->getRowIterator() as $key => $fila) {
                if ($key >= 3) {
                    $elemento = array();
                    //Obtenemos los datos de la fila y lo guardamos de el arreglo
                    foreach ($fila->getCellIterator() as $celda) {
                        # El valor, así como está en el documento
                        $valorRaw = trim($celda->getValue());
                        array_push($elemento, $valorRaw);
                    }
                    $codigo_producto_insertar = null;
                    if (isset($elemento[0]) && !empty($elemento[0])) {
                        $codigo_producto_insertar = trim($elemento[0]);
                    }

                    $nombre_producto_insertar = null;
                    if (isset($elemento[1]) && !empty($elemento[1])) {
                        $nombre_producto_insertar = trim($elemento[1]);
                        $nombre_producto_insertar = str_replace("  ", " ", $nombre_producto_insertar);
                        $nombre_producto_insertar = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'N'], $nombre_producto_insertar);
                        $nombre_producto_insertar = strtoupper($nombre_producto_insertar);
                    }
                    $codigo_barra_producto = null;
                    if (isset($elemento[2]) && !empty($elemento[2])) {
                        $codigo_barra_producto = trim($elemento[2]);
                        $codigo_barra_producto = str_replace("  ", " ", $codigo_barra_producto);
                        $codigo_barra_producto = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'N'], $codigo_barra_producto);
                        $codigo_barra_producto = strtoupper($codigo_barra_producto);
                    }

                    $tipo_producto_insertar = null;
                    if (isset($elemento[3]) && !empty($elemento[3])) {
                        $tipo_producto_insertar =  trim($elemento[3]);
                        $tipo_producto_insertar = strtoupper($tipo_producto_insertar);
                    }
                    $tipo_inventario_insertar = null;
                    if (isset($elemento[4]) && !empty($elemento[4])) {
                        $tipo_inventario_insertar = trim($elemento[4]);
                        $tipo_inventario_insertar = str_replace("  ", " ", $tipo_inventario_insertar);
                        $tipo_inventario_insertar = strtoupper($tipo_inventario_insertar);
                    }
                    $unidad_insertar = null;
                    if (isset($elemento[5]) && !empty($elemento[5])) {
                        $unidad_insertar = trim($elemento[5]);
                        $unidad_insertar = strtoupper($unidad_insertar);
                    }
                    $tipo_concentracion_insertar = null;
                    if (isset($elemento[6]) && !empty($elemento[6])) {
                        $tipo_concentracion_insertar = trim($elemento[6]);
                    }

                    $marca_insertar = null;
                    if (isset($elemento[7]) && !empty($elemento[7])) {
                        $marca_insertar =  trim($elemento[7]);
                        $marca_insertar = str_replace("  ", " ", $marca_insertar);
                        $marca_insertar = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['A', 'E', 'I', 'O', 'U', 'N'], $marca_insertar);
                        $marca_insertar = strtoupper($marca_insertar);
                    }
                    $nombre_proveedor_insertar = null;
                    if (isset($elemento[8]) && !empty($elemento[8])) {
                        $nombre_proveedor_insertar =  trim($elemento[8]);
                        $nombre_proveedor_insertar = str_replace("  ", " ", $nombre_proveedor_insertar);
                        $nombre_proveedor_insertar = strtoupper($nombre_proveedor_insertar);
                    }
                    $visibleonline_producto = null;
                    if (isset($elemento[9]) && !empty($elemento[9])) {
                        $visibleonline_producto =  trim($elemento[9]);
                        $visibleonline_producto = ($visibleonline_producto === "SI" || $visibleonline_producto === "si")   ? 1 : 0;
                    }
                    $precio_venta_insertar = null;
                    if (isset($elemento[10]) && !empty($elemento[10])) {
                        $precio_venta_insertar = trim($elemento[10]);
                    }

                    $categoria = null;
                    if (isset($elemento[11]) && !empty($elemento[11])) {
                        $categoria = preg_replace('/\s+/', ' ', $elemento[11]);
                        $categoria = trim($categoria);
                        $categoria = strtoupper($categoria);
                    }
                    $rut_imagenes = null;
                    if (isset($elemento[12]) && !empty($elemento[12])) {
                        $rut_imagenes = preg_replace('/\s+/', ' ', $elemento[12]);
                    }
                    $existeBodega = true;
                    $cantidaddatos = count($elemento);
                    $nbodega = null;
                    switch ($cantidaddatos) {
                        case 19:
                            $nbodega = 2;
                            break;
                        case 22:
                            $nbodega = 3;
                            break;
                        case 25:
                            $nbodega = 4;
                            break;
                        default:
                            $nbodega = 1;
                            break;
                    }
                    $numerobodega = 12;
                    for ($i = 0; $i < $nbodega; $i++) {
                        $numerobodega++;
                        $bodega = null;
                        if (isset($elemento[$numerobodega]) && !empty($elemento[$numerobodega])) {
                            $bodega =  trim($elemento[$numerobodega]);
                            $bodega = str_replace("  ", " ", $bodega);
                            $bodega = strtoupper($bodega);
                        }
                        $numerobodega += 2;
                        if ($bodega) {
                            $bodegas = Bodega::where('glosa_bodega', 'LIKE', "%$bodega%")->where('vigente_bodega', 1)->exists();
                            if (!$bodegas) {
                                $existeBodega = null;
                            }
                        } else {
                            $existeBodega = null;
                        }
                    }
                    if (
                        $tipo_producto_insertar === null || $tipo_inventario_insertar === null || $nombre_producto_insertar === null  || $precio_venta_insertar === null || $existeBodega === null
                    ) {
                        $fila = $key;
                        $datosnull = array(
                            "Tipo Producto" => $tipo_producto_insertar,
                            "Tipo Inventario" => $tipo_inventario_insertar,
                            "Nombre Producto" => $nombre_producto_insertar,
                            "Precio Venta" => $precio_venta_insertar,
                            "Bodega no existe" => $precio_venta_insertar,
                        );
                        $columnas = "";
                        foreach ($datosnull as $key => $element) {
                            if ($element === null) {
                                $columnas .= $key . ',';
                            }
                        }
                        $columnas = substr($columnas, 0, -1);
                        $comentario = "Campo vacio";
                        if ($existeBodega == null) {
                            $comentario = "Verificar las bodega(s), no existe";
                        }
                        $arreglocolumna = array(
                            "fila" => "Fila del Excel " . $fila,
                            "columna" => "Nombre Producto:$nombre_producto_insertar,Codigo Producto:$codigo_producto_insertar",
                            "comentario" => $comentario
                        );
                        array_push($validandoExcel, $arreglocolumna);
                        continue;
                    }
                    //VA DEPENDER DE LA FLAG SI CREA O ACTUALIZA--------------------
                    $Traendo_productos = Producto::where('codigo_producto', $codigo_producto_insertar)->first();
                    if ($_POST['tipo_accion'] !== "CREAR") {
                        if (!isset($Traendo_productos)) {
                            $elementos = [
                                "fila" => "Fila del Excel " . $key,
                                "columna" => "Nombre Producto:$nombre_producto_insertar,Codigo Producto:$codigo_producto_insertar",
                                "comentario" => "Producto  $codigo_producto_insertar,no existe Verificar."
                            ];
                            array_push($validandoExcel, $elementos);
                            continue;
                        }
                    } else {
                        if (isset($Traendo_productos)) {
                            $elementos = [
                                "fila" => "Fila del Excel " . $key,
                                "columna" => "Nombre Producto:$nombre_producto_insertar,Codigo Producto:$codigo_producto_insertar",
                                "comentario" => "Producto  $codigo_producto_insertar,existente Verificar."
                            ];
                            array_push($validandoExcel, $elementos);
                            continue;
                        }
                    }
                    //--------------------------------------------------------------

                    $tipoProducto = TipoProductos::where('glosa_tipo_producto', 'LIKE', "%$tipo_producto_insertar%")->first();
                    if (isset($tipoProducto)) {
                        $id_tipo_producto = $tipoProducto['id_tipo_producto'];
                    } else {
                        $tipoProductos = TipoProductos::create([
                            'glosa_tipo_producto' => $tipo_producto_insertar,
                            'vigente_tipo_producto' => 1
                        ]);
                        $id_tipo_producto = $tipoProductos->id_tipo_producto;
                    }

                    //CREA EL TIPO DE UNIDAD
                    $id_unidad = null;
                    if ($unidad_insertar) {
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
                    }
                    //SE VERIFICA EL TIPO DE CONCENTRACIÓN
                    $id_tipo_concentracion = null;
                    if ($tipo_concentracion_insertar) {
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
                    //--------------------------------------------------------------------
                    //TIPO INVENTARIO----------------------------
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
                    //-----------------------------------------------------------
                    //MARCA----------------------------
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
                    //------------------------------------------------------------------
                    //PROVEEDOR------------------------------------------------------
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
                    //------------------------------------------------------------------
                    $dataProducto = [
                        'id_tipo_producto' => $id_tipo_producto,
                        'id_tipo_concentracion' => $id_tipo_concentracion,
                        'id_tipo_inventario' => $id_tipo_inventario,
                        'id_unidad' => $id_unidad,
                        'id_marca' => $id_marca,
                        'id_proveedor' => $id_proveedor,
                        'glosa_producto' => $nombre_producto_insertar,
                        'precioventa_producto' => $precio_venta_insertar,
                        'codigo_barra_producto' => $codigo_barra_producto
                    ];

                    if ($_POST['tipo_accion'] == "CREAR") {
                        if ($codigo_producto_insertar === null) {
                            $codigo_producto_insertar = Helper::generarCodigoProducto($nombre_producto_insertar);
                        }
                        $urlAmigable_producto = "";
                        $urlAmigable_producto .= str_replace(" ", "-",  $codigo_producto_insertar);
                        $urlAmigable_producto = str_replace("/", "-", $urlAmigable_producto);
                        $urlAmigable_producto = str_replace("\\", "-", $urlAmigable_producto);
                        $urlAmigable_producto = str_replace("+", "-", $urlAmigable_producto);
                        $dataProducto += [
                            'id_tipo_afectacion' => 1,
                            'codigo_producto' => $codigo_producto_insertar,
                            'vigente_producto' => 1,
                            "fechacreacion_producto" => date('Y-m-d H:i:s'),
                            'urlamigable_producto' => $urlAmigable_producto,
                            'id_tipo_afectacion' => 1
                        ];
                        $codigorepetido = Producto::where('codigo_producto', $dataProducto['codigo_producto'])->exists();
                        if ($codigorepetido) {
                            $dataProductos = [
                                "fila" => "Fila del Excel " . $key,
                                "columna" => "Nombre Producto:$nombre_producto_insertar,Codigo Producto:$codigo_producto_insertar",
                                "comentario" => "Producto  $codigo_producto_insertar,existente Verificar."
                            ];
                            array_push($validandoExcel, $dataProductos);
                            continue;
                        }
                        $Producto = Producto::create($dataProducto);
                        $id_producto = $Producto->id_producto;
                        $dataHistorial = [
                            'id_tipo_movimiento' => 1
                        ];
                    } else {
                        Producto::where('id_producto', $Traendo_productos->id_producto)->update($dataProducto);
                        $dataHistorial = [
                            'id_tipo_movimiento' => 3
                        ];
                        $id_producto = $Traendo_productos->id_producto;
                    }

                    //STOCK PRODUCTO BODEGA
                    $numerobodega = 12;
                    for ($i = 0; $i < $nbodega; $i++) {
                        $numerobodega++;
                        $bodega = null;
                        if (isset($elemento[$numerobodega]) && !empty($elemento[$numerobodega])) {
                            $bodega =  trim($elemento[$numerobodega]);
                            $bodega = str_replace("  ", " ", $bodega);
                            $bodega = strtoupper($bodega);
                        }
                        $numerobodega++;
                        $stock = 0;
                        if (isset($elemento[$numerobodega]) && !empty($elemento[$numerobodega])) {
                            $stock =  trim($elemento[$numerobodega]);
                            $stock = str_replace("  ", " ", $stock);
                            $stock = strtoupper($stock);
                        }
                        $numerobodega++;
                        $precio_compra = 0;
                        if (isset($elemento[$numerobodega]) && !empty($elemento[$numerobodega])) {
                            $precio_compra =  trim($elemento[$numerobodega]);
                            $precio_compra = str_replace("  ", " ", $precio_compra);
                            $precio_compra = strtoupper($precio_compra);
                        }
                        $bodegas = Bodega::where('glosa_bodega', 'LIKE', "%$bodega%")->where('vigente_bodega', 1)->first();
                        $id_bodega = $bodegas->id_bodega;
                        $databodega = [
                            'total_stock_producto_bodega' => $stock,
                            'ultimopreciocompra_stock_producto_bodega' => $precio_compra
                        ];
                        if ($_POST['tipo_accion'] == "CREAR") {
                            $databodega += [
                                'id_producto' => $id_producto,
                                'id_bodega' => $id_bodega
                            ];
                            StockProductoBodega::create($databodega);
                            $id_tipo_movimiento = 1;
                        } else {
                            StockProductoBodega::where('id_bodega', $id_bodega)->where('id_producto', $id_producto)->update($databodega);
                            $id_tipo_movimiento = 3;
                        }
                        //HISTORIAL
                        $dataHistorial += [
                            'id_tipo_movimiento' => $id_tipo_movimiento,
                            'id_usuario' => $_POST['id_usuario'],
                            'id_producto' => $id_producto,
                            'id_bodega' => $id_bodega,
                            'cantidadmovimiento_producto_historial' => $stock,
                            'fecha_producto_historial' => date('Y-m-d H:i:s'),
                            'comentario_producto_historial' => "MIGRADO DESDE EL EXCEL.",
                            'preciocompra_producto_historial' => $precio_compra
                        ];
                        ProductoHistorial::create($dataHistorial);
                    }


                    //CATEGORIA------------------------------
                    if ($categoria) {
                        $Categorias = explode(',', $categoria);
                        $id_categoria_padre = 0;
                        foreach ($Categorias as $key => $element) {
                            $element = trim(preg_replace('/\s+/', ' ', $element));
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
                            'id_producto' => $id_producto
                        ];
                        CategoriaProducto::create($datos);
                    }
                    //------------------------------------
                    //IMAGENES---------------------------------
                    if ($rut_imagenes) {
                        $imagenes = explode("|", $rut_imagenes);
                        $portada = count($imagenes) > 1 ? 0 : 1;
                        $imagenesruta = ProductoImagen::where('id_producto', $id_producto)->pluck('url_producto_imagen')->toArray();
                        $imageneseliminar = array_diff($imagenesruta, $imagenes);
                        ProductoImagen::whereIn('url_producto_imagen', $imageneseliminar)->delete();
                        $aregarimagen = array_diff($imagenes, $imagenesruta);
                        foreach ($aregarimagen as $key => $imagen) {
                            // FILTRA LOS DATOS DE LA IMAGEN PARA TRAER EL ID Y PODER ELIMINAR
                            $search = new \Cloudinary\Search;
                            $search_result = $search->expression("filename:" . basename($imagen))->execute();
                            //
                            $dataimagen = [
                                'id_producto' => $id_producto,
                                'fechacreacion_producto_imagen' => date('Y-m-d H:i:s'),
                                'estado_producto_imagen' => 1,
                                'orden_producto_imagen' => $key,
                                'portada_producto_imagen' => $portada,
                                'public_id_producto_imagen' =>  $search_result["resources"][0]["public_id"],
                                'url_producto_imagen' => $imagen
                            ];
                            ProductoImagen::create($dataimagen);
                        }
                    }
                    // ------------------------------------------
                }
            }
        } catch (\Throwable $e) {
            $respuesta = $e->getMessage();
            echo "echo <br>" . $respuesta;
            exit();
        }


        $retornando = [
            "respuesta" => $respuesta,
            "respuesta_producto_registrado" => $ProductoNoRegistrado,
            "validandoExcel" => $validandoExcel
        ];
        unlink('archivo/ImportarExcelProducto/' . $nombre_excel);
        echo  json_encode($retornando);
    }

    public function exportarDatos()
    {

        $encabezado = [
            "Codigo Producto", "Nombre del Producto  (Obligatorio)", "Codigo Barra", "Tipo Producto (Obligatorio)", "Tipo Inventario (Obligatorio)", "Unidad", "Tipo Concentración",
            "Marca", "Nombre del Laboratorio", 'Visible Online', "Precio Venta (Obligatorio)", "Categoria (separado con '|' si esta relacionado con varias categorias)",
            "Ruta Imagenes(separado con '|' si esta relacionado con varias imagenes)"
        ];
        $encabezado = array_map('strtoupper', $encabezado);
        $bodegas = "SELECT null as BODEGA,null as STOCK,null as 'PRECIO COMPRA' FROM bodega";
        $bodega = (new ConsultaGlobal())->ConsultaGlobal($bodegas);
        $columnas = range('N', 'Z');
        $contadorBodega = 1;
        $cantida_bodega = count($bodega) * 3;
        $cantida_bodega -= 1;
        foreach ($bodega as &$bodegaData) {
            $tempArray = [
                'BODEGA ' . $contadorBodega . '(OBLIGATORIO)',
                'STOCK ' . $contadorBodega,
                'PRECIO COMPRA ' . $contadorBodega
            ];
            $encabezado = array_merge($encabezado, $tempArray);
            $contadorBodega++;
        }
        $consulta = "SELECT *,
        (SELECT GROUP_CONCAT(id_categoria)
            from categoria_producto where id_producto=producto.id_producto
            ) as categoria_producto,
        (SELECT GROUP_CONCAT(url_producto_imagen SEPARATOR '|')
            from producto_imagen where id_producto=producto.id_producto
        ) as producto_imagen,
        (
            SELECT  GROUP_CONCAT(glosa_bodega,'@',total_stock_producto_bodega,'@',ultimopreciocompra_stock_producto_bodega SEPARATOR '|') from stock_producto_bodega
            inner join bodega using (id_bodega)
            where id_producto=producto.id_producto
        ) as stock_producto_bodega
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
        // $comando = __DIR__ . '/../Helpers/python/exportar.py';
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
        // AGREGAR IMAGEN AL EXCEL---------------------------------------------------
        // $gdImage = imagecreatefrompng(RUTA_ARCHIVO . "/archivo/imagenes/ahorro_farma.png");
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing();
        // $drawing->setName('In-Memory image 1');
        // $drawing->setDescription('In-Memory image 1');
        // $drawing->setCoordinates('A1');
        // $drawing->setImageResource($gdImage);
        // $drawing->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_JPEG);
        // $drawing->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
        // $drawing->setWidth(80);
        // $drawing->setHeight(20);
        // $drawing->setWorksheet($spread->getActiveSheet());
        // ---------------------------------------------------------------------------

        $letrabodega = $columnas[$cantida_bodega];
        $celda = "A1:{$letrabodega}1";
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
        $this->cellColor("A2:{$letrabodega}2", 'DFE2E1', $sheet);
        // $this->cellColor('A2:D2', 'D8EA39', $sheet);
        // $this->cellColor('M2:N2', 'D8EA39', $sheet);
        // $this->cellColor('E2:K2', 'DFE2E1', $sheet);

        # El último argumento es por defecto A1
        $sheet->fromArray($encabezado, null, 'A2');
        $sheet->fromArray(["INVENTARIO DEL PRODUCTO"], null, 'A1');

        //SE UNE LAS CELDAS
        $sheet->mergeCells($celda);
        foreach (range('A', $letrabodega) as $columnID) {
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
            $sheet->setCellValue("C$indice", $elemento->codigo_barra_producto);
            $sheet->setCellValue("D$indice", $elemento->glosa_tipo_producto);
            $sheet->setCellValue("E$indice", $elemento->glosa_tipo_inventario);
            $sheet->setCellValue("F$indice", $elemento->glosa_unidad);
            $sheet->setCellValue("G$indice", $elemento->glosa_tipo_concentracion);
            $sheet->setCellValue("H$indice", $elemento->glosa_marca);
            $sheet->setCellValue("I$indice", $elemento->glosa_proveedor);

            $sheet->setCellValue("J$indice", ($elemento->visibleonline_producto == 1)  ? 'SI' : 'NO');
            $sheet->setCellValue("K$indice", $elemento->precioventa_producto);
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
                        if (isset($Categorias)) {
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
            $sheet->setCellValue("L$indice", $mostar_categorias);
            $sheet->setCellValue("M$indice", $elemento->producto_imagen);

            if ($elemento->stock_producto_bodega) {
                $stock_producto_bodega = explode("|", $elemento->stock_producto_bodega);
                // Inicializar el índice de fila
                $fila = 0;

                foreach ($stock_producto_bodega as $element) {
                    // Incrementar el índice de fila en cada iteración
                    $stock_producto = explode("@", $element);
                    foreach ($stock_producto as $dato) {
                        $sheet->setCellValue($columnas[$fila] . $indice, $dato);
                        $fila++;
                    }
                }
            }

            // ----------------------------------------------------------------
            //ASIGANMOS A CADA CELDA EL SELECT multiple
            // GLOSA TIPO PRODUCTO
            $validation = $sheet->getCell('D' . ($indice))->getDataValidation();
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
            //TIPO INVENTARIO
            if (count($tipo_inventario) > 0) {
                $validation = $sheet->getCell('E' . ($indice))->getDataValidation();
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
                $validation = $sheet->getCell('F' . ($indice))->getDataValidation();
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
            // //ASIGANMOS A CADA CELDA EL SELECT multiple
            // //TIPO CONCENTRACION
            if (count($TipoConcentracion) > 0) {
                $validation = $sheet->getCell('G' . ($indice))->getDataValidation();
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
            // //ASIGANMOS A CADA CELDA EL SELECT multiple
            // //MARCA
            if (count($marca) > 0) {
                $validation = $sheet->getCell('H' . ($indice))->getDataValidation();
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
    public function ExportarPlantilla()
    {
        $encabezado = [
            "Codigo Producto", "Nombre del Producto  (Obligatorio)", "Codigo Barra", "Tipo Producto (Obligatorio)", "Tipo Inventario (Obligatorio)", "Unidad", "Tipo Concentración",
            "Marca", "Nombre del Laboratorio", 'Visible Online', "Precio Venta (Obligatorio)", "Categoria (separado con '|' si esta relacionado con varias categorias)",
            "Ruta Imagenes(separado con '|' si esta relacionado con varias imagenes)"
        ];
        $bodega = Bodega::all();
        $columnas = range('N', 'Z');
        $cantida_bodega = count($bodega) * 3;
        $cantida_bodega -= 1;
        $celda_bodega = $columnas[$cantida_bodega];
        $celda = "A1:{$celda_bodega}1";
        $fechacreacion = date('Y-m-d');
        $contadorBodega = 1;
        foreach ($bodega as &$bodegaData) {
            $tempArray = [
                'BODEGA ' . $contadorBodega . '(OBLIGATORIO)',
                'STOCK ' . $contadorBodega,
                'PRECIO COMPRA ' . $contadorBodega
            ];
            $encabezado = array_merge($encabezado, $tempArray);
            $contadorBodega++;
        }
        $spread = new Spreadsheet();
        $sheet = $spread->getActiveSheet();
        // AGREGAR IMAGEN AL EXCEL
        // $gdImage = imagecreatefrompng(RUTA_ARCHIVO . "/archivo/imagenes/ahorro_farma.png");
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing();
        // $drawing->setName('In-Memory image 1');
        // $drawing->setDescription('In-Memory image 1');
        // $drawing->setCoordinates('A1');
        // $drawing->setImageResource($gdImage);
        // $drawing->setRenderingFunction(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::RENDERING_JPEG);
        // $drawing->setMimeType(\PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_DEFAULT);
        // $drawing->setWidth(80);
        // $drawing->setHeight(20);
        // $drawing->setWorksheet($spread->getActiveSheet());

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
        $this->cellColor("A2:{$celda_bodega}2", 'DFE2E1', $sheet);
        // $this->cellColor('A2:D2', 'D8EA39', $sheet);
        // $this->cellColor('M2:N2', 'D8EA39', $sheet);
        // $this->cellColor('E2:K2', 'DFE2E1', $sheet);


        $encabezado = array_map('strtoupper', $encabezado);
        # El último argumento es por defecto A1
        $sheet->fromArray($encabezado, null, "A2");
        $sheet->fromArray(["INVENTARIO DEL PRODUCTO"], null, 'A1');

        //SE UNE LAS CELDAS
        $sheet->mergeCells($celda);
        foreach (range('A', $celda_bodega) as $columnID) {
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
        $validation = $sheet->getCell('D' . ($indice))->getDataValidation();
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
        //TIPO INVENTARIO
        if (count($tipo_inventario) > 0) {
            $validation = $sheet->getCell('E' . ($indice))->getDataValidation();
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
            $validation = $sheet->getCell('F' . ($indice))->getDataValidation();
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
        // //ASIGANMOS A CADA CELDA EL SELECT multiple
        // //TIPO CONCENTRACION
        if (count($TipoConcentracion) > 0) {
            $validation = $sheet->getCell('G' . ($indice))->getDataValidation();
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
        // //ASIGANMOS A CADA CELDA EL SELECT multiple
        // //MARCA
        if (count($marca) > 0) {
            $validation = $sheet->getCell('H' . ($indice))->getDataValidation();
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
