<?php


if (isset($_GET['consultaquery'])) {
    require_once "models/ConsultaGlobal.php";
} else {
    require_once "models/Producto.php";
    require_once "models/ProductoHistorial.php";
}


class ProductoController
{
    public function ListaProducto()
    {
        // FILTROS DEL DATATABLE ANGULAR
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;
        $recordsFilteredTotal = Producto::where("producto.vigente_producto", 1);
        if (isset($DatosPost->categoria_padres)) {
            $recordsFilteredTotal = $recordsFilteredTotal->join('categoria_producto', 'categoria_producto.id_categoria_producto', "producto.id_producto")
                ->whereIn("categoria_producto.id_categoria", $DatosPost->categoria_padres);
        }
        if (isset($DatosPost->glosa_producto)) {
            $recordsFilteredTotal = $recordsFilteredTotal->where('producto.glosa_producto', 'LIKE', "%$DatosPost->glosa_producto%");
        }
        if (isset($DatosPost->sku_producto)) {
            $recordsFilteredTotal = $recordsFilteredTotal->where('producto.codigo_producto', 'LIKE', "%$DatosPost->sku_producto%");
        }
        if (isset($DatosPost->id_tipo_inventario)) {
            $recordsFilteredTotal = $recordsFilteredTotal->where('producto.id_tipo_inventario', $DatosPost->id_tipo_inventario);
        }
        if (!empty($buscar)) {
            $recordsFilteredTotal = $recordsFilteredTotal->Where(function ($query) use ($buscar) {
                $query->where('producto.glosa_producto', 'LIKE', "%$buscar%")
                    ->orWhere('producto.codigo_producto', 'LIKE', "%$buscar%")
                    ->orWhere('producto.codigo_barra_producto', 'LIKE', "%$buscar%")
                    ->orWhere('producto.precioventa_producto', 'LIKE', "%$buscar%");
            });
        }
        $listaProducto = $recordsFilteredTotal;
        $recordsFilteredTotales = $recordsFilteredTotal->get()->count();
        $listaProducto = $listaProducto->orderBy('producto.id_producto', 'desc')->skip($DatosPost->start)->take($longitud)->get();
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => $recordsFilteredTotales,
            "recordsFiltered" => $recordsFilteredTotales,
            "data" => $listaProducto
        );
        echo json_encode($datos);
    }


    public function ListaProductoDeshabilitado()
    {
        // FILTROS DEL DATATABLE ANGULAR
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;

        $recordsFilteredTotal = Producto::where("producto.vigente_producto", 0);
        if (isset($DatosPost->categoria_padres)) {
            $recordsFilteredTotal = $recordsFilteredTotal->join('categoria_producto', 'categoria_producto.id_categoria_producto', "producto.id_producto")
                ->whereIn("categoria_producto.id_categoria", $DatosPost->categoria_padres);
        }
        if (isset($DatosPost->glosa_producto)) {
            $recordsFilteredTotal = $recordsFilteredTotal->where('producto.glosa_producto', 'LIKE', "%$DatosPost->glosa_producto%");
        }
        if (isset($DatosPost->sku_producto)) {
            $recordsFilteredTotal = $recordsFilteredTotal->where('producto.codigo_producto', 'LIKE', "%$DatosPost->sku_producto%");
        }
        if (isset($DatosPost->id_tipo_inventario)) {
            $recordsFilteredTotal = $recordsFilteredTotal->where('producto.id_tipo_inventario', $DatosPost->id_tipo_inventario);
        }
        if (!empty($buscar)) {
            $recordsFilteredTotal = $recordsFilteredTotal->Where(function ($query) use ($buscar) {
                $query->where('glosa_producto', 'LIKE', "%$buscar%")
                    ->orWhere('codigo_producto', 'LIKE', "%$buscar%")
                    ->orWhere('codigo_barra_producto', 'LIKE', "%$buscar%")
                    ->orWhere('precioventa_producto', 'LIKE', "%$buscar%");
            });
        }
        $listaProducto = $recordsFilteredTotal;
        $recordsFilteredTotales = $recordsFilteredTotal->get()->count();
        $listaProducto = $listaProducto->orderBy('producto.id_producto', 'desc')->skip($DatosPost->start)->take($longitud)->get();
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => $recordsFilteredTotales,
            "recordsFiltered" => $recordsFilteredTotales,
            "data" => $listaProducto
        );
        echo json_encode($datos);
    }

    public function GestionarStockProducto()
    {

        $GestionarStock = json_decode($_POST['GestionarStock']);
        $datos = array(
            "id_usuario" => $GestionarStock->id_usuario,
            "id_tipo_movimiento" => $GestionarStock->accion,
            "comentario_producto_historial" => $GestionarStock->comentario,
            "preciocompra_producto_historial" => $GestionarStock->precio_compra,
            "cantidadrmovimiento_producto_historial" => $GestionarStock->stock_final,
            "fecha_producto_historial" => date('Y-m-d H:i:s')
        );
        ProductoHistorial::create($datos);
        echo json_encode('ok');
    }
    public function GestionActivoDesactivadoProducto()
    {
        if ($_POST['accion'] == 'ACTIVAR') {
            $data = [
                'vigente_producto' => 1
            ];
        } else {
            $data = [
                'vigente_producto' => 0
            ];
        }
        Producto::where("id_producto", $_POST['id_producto'])->update($data);
        echo json_encode("exitoso");
    }

    public function TraerDatosProductos()
    {

        $consulta = " WHERE id_producto= {$_GET['id_producto']}";
        $ConsultaGlobal = (new ConsultaGlobal())->TraerDatosProductos($consulta);


        //SACAMOS LOS PRODUCTO RELACIONADOS
        $arreglo_relacionado = [];
        if ($ConsultaGlobal->producto_relacionado) {
            $producto_relacionado = $ConsultaGlobal->producto_relacionado;
            $producto_relacionado = explode('~', $producto_relacionado);
            if (count($producto_relacionado) > 0) {
                foreach ($producto_relacionado as $key => $element) {
                    $consulta = " WHERE id_producto= {$element}  ";
                    $ConsultRelacionado = (new ConsultaGlobal())->ConsultaProductosRelacionado($consulta);
                    $path_producto_imagen = __DIR__ . "/../archivo/imagen_producto/{$ConsultRelacionado->path_producto_imagen}";
                    if (is_file($path_producto_imagen)) {
                        // $path_producto_imagen = base64_encode(file_get_contents($path_producto_imagen));
                        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
                        $domain = $_SERVER['HTTP_HOST'];
                        $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_producto/$ConsultRelacionado->path_producto_imagen";
                    } else {
                        $imagens = '';
                    }

                    $element = [
                        'path_producto_imagen' => $imagens,
                        'glosa_producto' => $ConsultRelacionado->glosa_producto,
                        'id_producto' => $ConsultRelacionado->id_producto,
                    ];
                    array_push($arreglo_relacionado, $element);
                }
            }
        }
        //
        //SACAMOS COLOR PRODUCTO 
        $arreglo_color = [];
        if ($ConsultaGlobal->color_producto) {
            $color_producto = $ConsultaGlobal->color_producto;
            $color_producto = explode('~', $color_producto);
            foreach ($color_producto as $key => $element) {
                $elementos = explode('|', $element);
                $id_producto_color = $elementos[1];
                $hexadecimal_producto_color = $elementos[0];
                $nombre_producto_color = $elementos[2];
                $datos = [
                    'id_producto_color' => $id_producto_color,
                    'hexadecimal_producto_color' => $hexadecimal_producto_color,
                    'nombre_producto_color' => $nombre_producto_color
                ];
                array_push($arreglo_color, $datos);
            }
        }

        //SACAMOS LAS IMAGENES DE LOS PRODUCTOS
        $arreglo_imagen = [];
        if ($ConsultaGlobal->producto_imagen) {
            $producto_imagen = $ConsultaGlobal->producto_imagen;
            $producto_imagen = explode('~', $producto_imagen);
            if (count($producto_imagen) > 0) {
                foreach ($producto_imagen as $key => $element) {
                    $elementos = explode('|', $element);
                    $id_producto_imagen = $elementos[0];
                    $nombre_producto_imagen = $elementos[1];
                    $path_producto_imagen = $elementos[2];
                    $portada_imagen = isset($elementos[3]) ? $elementos[3] : 0;
                    $imagen_base_64 = __DIR__ . "/../archivo/imagen_producto/{$path_producto_imagen}";
                    if (is_file($imagen_base_64)) {
                        // $imagenComoBase64 = base64_encode(file_get_contents($imagen_base_64));
                        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
                        $domain = $_SERVER['HTTP_HOST'];
                        $imagenComoBase64 = $protocol . $domain . "/MVC_CRM/archivo/imagen_producto/$path_producto_imagen";
                    } else {
                        $imagenComoBase64 = '';
                    }
                    $datos = [
                        'id_producto_imagen' => $id_producto_imagen,
                        'nombre_imagen' => $nombre_producto_imagen,
                        'orden_imagen' => $key + 1,
                        "imagen" => $imagenComoBase64,
                        "portada" => ($portada_imagen === "1") ? true : false
                    ];
                    array_push($arreglo_imagen, $datos);
                }
            }
        }
        //

        //SACAMOS ESPECIFICACIOENS DE LOS PRODUCTOS
        $arreglo_especificacion = [];
        if ($ConsultaGlobal->producto_especificaciones) {
            $producto_especificaciones = $ConsultaGlobal->producto_especificaciones;
            $producto_especificaciones = explode('~', $producto_especificaciones);
            if (count($producto_especificaciones) > 0) {
                foreach ($producto_especificaciones as $key => $element) {
                    $elementos = explode('|', $element);
                    $id_especificaciones_producto = $elementos[0];
                    $glosa_especificaciones_producto = $elementos[1];
                    $respuesta_especificaciones_producto = $elementos[2];

                    $datos = [
                        'id_especificaciones_producto' => $id_especificaciones_producto,
                        'glosa_especificaciones_producto' => $glosa_especificaciones_producto,
                        'respuesta_especificaciones_producto' => $respuesta_especificaciones_producto,

                    ];
                    array_push($arreglo_especificacion, $datos);
                }
            }
        }

        //

        //SACAMOS ESPECIFICACIOENS DE LOS PRODUCTOS
        $arreglo_categoria_producto = [];

        if ($ConsultaGlobal->categoria_producto) {
            $categoria_producto = $ConsultaGlobal->categoria_producto;
            $categoria_producto = explode('~', $categoria_producto);
            if (count($categoria_producto) > 0) {
                foreach ($categoria_producto as $key => $element) {
                    $elementos = explode('|', $element);
                    $id_categoria_producto = $elementos[0];
                    $id_categoria = $elementos[1];
                    $datos = [
                        'id_categoria_producto' => $id_categoria_producto,
                        'id_categoria' => $id_categoria
                    ];
                    array_push($arreglo_categoria_producto, $datos);
                }
            }
        }
        //

        //SACAMOS ESPECIFICACIOENS DE LOS PRODUCTOS
        $arreglo_atributo_producto = [];
        if ($ConsultaGlobal->atributo_producto) {
            $atributo_producto = $ConsultaGlobal->atributo_producto;
            $atributo_producto = explode('~', $atributo_producto);
            if (count($atributo_producto) > 0) {
                foreach ($atributo_producto as $key => $element) {
                    $elementos = explode('|', $element);
                    $id_atributo_producto = $elementos[0];
                    $id_atributo = $elementos[1];
                    $glosa_atributo = $elementos[2];
                    $stock_atributo = $elementos[3];
                    $datos = [
                        'id_atributo_producto' => intval($id_atributo_producto),
                        'id_atributo' => intval($id_atributo),
                        'glosa_atributo' => $glosa_atributo,
                        'cantidad' => intval($stock_atributo),
                    ];
                    array_push($arreglo_atributo_producto, $datos);
                }
            }
        }
        //

        $fillable = [
            'id_producto' => $ConsultaGlobal->id_producto,
            'id_tipo_producto' => $ConsultaGlobal->id_tipo_producto,
            'id_tipo_concentracion' => $ConsultaGlobal->id_tipo_concentracion,
            'id_tipo_inventario' => $ConsultaGlobal->id_tipo_inventario,
            'id_unidad' => $ConsultaGlobal->id_unidad,
            'id_marca' => $ConsultaGlobal->id_marca,
            'codigo_producto' => $ConsultaGlobal->codigo_producto,
            'glosa_producto' => $ConsultaGlobal->glosa_producto,
            'detalle_producto' => $ConsultaGlobal->detalle_producto,
            'detallelargo_producto' => $ConsultaGlobal->detallelargo_producto,
            'multidosis_producto' => $ConsultaGlobal->multidosis_producto,
            'dosis_producto' => $ConsultaGlobal->dosis_producto,
            'concentracion_producto' => $ConsultaGlobal->concentracion_producto,
            'cantidad_producto' => $ConsultaGlobal->cantidad_producto,
            'stock_producto' => $ConsultaGlobal->stock_producto,
            'precioventa_producto' => $ConsultaGlobal->precioventa_producto,
            'preciocosto_producto' => $ConsultaGlobal->preciocosto_producto,
            'fechacreacion_producto' => $ConsultaGlobal->fechacreacion_producto,
            'saldocantidad_producto' => $ConsultaGlobal->saldocantidad_producto,
            'contenidomultidosis_producto' => $ConsultaGlobal->contenidomultidosis_producto,
            'urlamigable_producto' => $ConsultaGlobal->urlamigable_producto,
            'vigente_producto' => $ConsultaGlobal->vigente_producto,
            'visibleonline_producto' => $ConsultaGlobal->visibleonline_producto,
            "arreglo_relacionado" => $arreglo_relacionado,
            "arreglo_color" => $arreglo_color,
            "arreglo_imagen" => $arreglo_imagen,
            "arreglo_especificacion" => $arreglo_especificacion,
            "arreglo_categoria_producto" => $arreglo_categoria_producto,
            "arreglo_atributo_producto" => $arreglo_atributo_producto,
            "producto_relacionado" => $ConsultaGlobal->producto_relacionado
        ];

        echo json_encode($fillable);
    }

    public function VerificarSku()
    {
        $producto = Producto::where('codigo_producto', $_GET['codigo_producto']);
        if ($_GET['id_producto'] !== 'null') {
            $producto = $producto->where('id_producto', '!=', $_GET['id_producto']);
        }
        $producto = $producto->first();
        if (isset($producto)) {
            echo json_encode(false);
            http_response_code(403);
            die;
        } else {
            echo json_encode(true);
        }
    }
}
