<?php
require_once "models/Producto.php";
require_once "models/ProductoHistorial.php";
require_once "models/ConsultaGlobal.php";
require_once "models/TipoAfectacion.php";
require_once "models/ProductoImagen.php";
require_once "models/TipoInventario.php";
require_once "models/Bodega.php";
require_once "models/Unidad.php";
require_once "models/TipoConcentracion.php";
require_once "models/StockProductoBodega.php";


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
        // Definir la consulta base
        $query = "SELECT
            *,
            (SELECT GROUP_CONCAT(glosa_bodega,'@',total_stock_producto_bodega  SEPARATOR '|')
            FROM stock_producto_bodega
            INNER JOIN bodega using (id_bodega)
            where id_producto=producto.id_producto
            ) as total_stock_producto_bodega
         FROM producto WHERE vigente_producto = 1";

        // Aplicar filtros
        if (isset($DatosPost->categoria_padres)) {
            $query .= " JOIN categoria_producto ON categoria_producto.id_categoria_producto = producto.id_producto";
            $query .= " WHERE categoria_producto.id_categoria IN (" . implode(",", $DatosPost->categoria_padres) . ")";
        }

        if (isset($DatosPost->glosa_producto)) {
            $query .= " AND producto.glosa_producto LIKE '%" . $DatosPost->glosa_producto . "%'";
        }

        if (isset($DatosPost->sku_producto)) {
            $query .= " AND producto.codigo_producto LIKE '%" . $DatosPost->sku_producto . "%'";
        }

        if (isset($DatosPost->id_tipo_inventario)) {
            $query .= " AND producto.id_tipo_inventario = " . $DatosPost->id_tipo_inventario;
        }

        if (!empty($buscar)) {
            $query .= " AND (producto.glosa_producto LIKE '%$buscar%' OR ";
            $query .= "producto.codigo_producto LIKE '%$buscar%' OR ";
            $query .= "producto.codigo_barra_producto LIKE '%$buscar%' OR ";
            $query .= "producto.precioventa_producto LIKE '%$buscar%')";
        }
        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= " ORDER BY producto.id_producto DESC  LIMIT {$longitud} OFFSET $DatosPost->start ";
        $consultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $consultaGlobal
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

    public function ProductoHistorial()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);

        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;

        $recordsFilteredTotal = ProductoHistorial::join('usuario', 'usuario.id_usuario', 'producto_historial.id_usuario')
            ->join('bodega', 'bodega.id_bodega', 'producto_historial.id_bodega')
            ->leftjoin("staff", 'staff.id_staff', 'usuario.id_staff', 'bodega.glosa_bodega')
            ->leftjoin("tipo_movimiento", 'tipo_movimiento.id_tipo_movimiento', 'producto_historial.id_tipo_movimiento')
            ->leftjoin("tipo_documento", 'tipo_documento.id_tipo_documento', 'producto_historial.id_tipo_documento')
            ->where("producto_historial.id_producto", $DatosPost->id_producto);
        if (!empty($buscar)) {
            $recordsFilteredTotal = $recordsFilteredTotal->Where(function ($query) use ($buscar) {
                $query->where('tipo_movimiento.glosa_tipo_movimiento', 'LIKE', "%$buscar%")
                    ->orWhere('staff.nombre_staff', 'LIKE', "%$buscar%")
                    ->orWhere('staff.apellidopaterno_staff', 'LIKE', "%$buscar%")
                    ->orWhere('staff.apellidomaterno_staff', 'LIKE', "%$buscar%")
                    ->orWhere('producto_historial.comentario_producto_historial', 'LIKE', "%$buscar%")
                    ->orWhere('producto_historial.fecha_producto_historial', 'LIKE', "%$buscar%")
                    ->orWhere('producto_historial.cantidadmovimiento_producto_historial', 'LIKE', "%$buscar%");
            });
        }
        $listaProducto = $recordsFilteredTotal;
        $recordsFilteredTotales = $recordsFilteredTotal->get()->count();
        $listaProducto = $listaProducto->orderBy('producto_historial.fecha_producto_historial', 'desc')->skip($DatosPost->start)->take($longitud)->get();
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
            "cantidadmovimiento_producto_historial" => $GestionarStock->cantidad,
            "id_producto" => $GestionarStock->id_producto,
            "id_bodega" => $GestionarStock->id_bodega,
            "fecha_producto_historial" => date('Y-m-d H:i:s')
        );
        ProductoHistorial::create($datos);
        $producto = StockProductoBodega::where('id_producto', $GestionarStock->id_producto)->where('id_bodega', $GestionarStock->id_bodega)->first();
        switch ($GestionarStock->accion) {
            case '1':
                $producto->total_stock_producto_bodega += $GestionarStock->cantidad;
                break;
            case '2':
                $producto->total_stock_producto_bodega -= $GestionarStock->cantidad;
                break;
        }
        if ($GestionarStock->precio_compra) {
            $producto->ultimopreciocompra_stock_producto_bodega = $GestionarStock->precio_compra;
        }
        $producto->save();
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
        $datos = $this->ObtenerDatosProducto($consulta);
        $unidad = Unidad::where('vigente_unidad', 1)->get();
        $tipo_concentracion = TipoConcentracion::where('vigente_tipo_concentracion', 1)->get();
        $respuesta = [
            "producto" => $datos,
            'unidad' => $unidad,
            "tipo_concentracion" => $tipo_concentracion
        ];
        echo json_encode($respuesta);
    }
    public function TraerProductos()
    {
        $tipo_inventario = TipoInventario::where("vigente_tipo_inventario", 1)->get();
        $tipo_afectacion = TipoAfectacion::where('vigente_afectacion', 1)->get();
        $query_bodega = 'SELECT id_bodega,glosa_bodega, 0 AS total_stock_producto_bodega, 0 AS ultimopreciocompra_stock_producto_bodega
        FROM bodega
        WHERE vigente_bodega = 1';
        $bodegas = (new ConsultaGlobal())->ConsultaGlobal($query_bodega);
        $unidad = Unidad::where('vigente_unidad', 1)->get();
        $tipo_concentracion = TipoConcentracion::where('vigente_tipo_concentracion', 1)->get();
        $respuesta = [
            'tipo_inventario' => $tipo_inventario,
            'tipo_afectacion' => $tipo_afectacion,
            'bodegas' => $bodegas,
            'unidad' => $unidad,
            "tipo_concentracion" => $tipo_concentracion
        ];
        echo json_encode($respuesta);
    }

    public function ObtenerDatosProducto($consulta)
    {
        $ConsultaGlobal = (new ConsultaGlobal())->TraerDatosProductos($consulta);
        //SACAMOS LOS PRODUCTO RELACIONADOS
        $arreglo_relacionado = [];
        if ($ConsultaGlobal->producto_relacionado) {
            $producto_relacionado = $ConsultaGlobal->producto_relacionado;
            $producto_relacionado = explode('~', $producto_relacionado);
            if (count($producto_relacionado) > 0) {
                foreach ($producto_relacionado as $key => $element) {
                    $producto = explode('|', $element);
                    $id_producto = $producto[0];
                    $glosa_producto = $producto[1];
                    $codigo_producto = $producto[2];
                    $id_producto_relacionado = $producto[3];
                    $consultRelacionado = ProductoImagen::where('id_producto', $id_producto)->where('portada_producto_imagen', 1)->value('url_producto_imagen');
                    $element = [
                        'id_producto_relacionado' => $id_producto_relacionado,
                        'codigo_producto' => $codigo_producto,
                        'path_producto_imagen' => $consultRelacionado,
                        'glosa_producto' => $glosa_producto,
                        'id_producto' => $id_producto,
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
                    $url_producto_imagen = $elementos[2];
                    $portada_imagen = isset($elementos[3]) ? $elementos[3] : 0;
                    $datos = [
                        'id_producto_imagen' => $id_producto_imagen,
                        'nombre_imagen' => $nombre_producto_imagen,
                        'orden_imagen' => $key + 1,
                        "imagen" => $url_producto_imagen,
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

        //TRAMOS LAS STOCK BODEGA
        $stock_producto_bodega = [];
        if ($ConsultaGlobal->stock_producto_bodega) {
            $producto_bodega = $ConsultaGlobal->stock_producto_bodega;
            $producto_bodega = explode('~', $producto_bodega);
            if (count($producto_bodega) > 0) {
                foreach ($producto_bodega as $key => $element) {
                    $elementos = explode('|', $element);
                    $glosa_bodega = $elementos[1];
                    $total_stock_producto_bodega = $elementos[2];
                    $ultimopreciocompra_stock_producto_bodega = $elementos[3];
                    $datos = [
                        'glosa_bodega' => $glosa_bodega,
                        'total_stock_producto_bodega' => $total_stock_producto_bodega,
                        'ultimopreciocompra_stock_producto_bodega' => $ultimopreciocompra_stock_producto_bodega
                    ];
                    array_push($stock_producto_bodega, $datos);
                }
            }
        }
        //
        $tipoAfectacion = TipoAfectacion::where('vigente_afectacion', 1)->get();
        $fillable = [
            'id_producto' => $ConsultaGlobal->id_producto,
            'id_tipo_producto' => $ConsultaGlobal->id_tipo_producto,
            'id_tipo_concentracion' => $ConsultaGlobal->id_tipo_concentracion,
            'id_tipo_inventario' => $ConsultaGlobal->id_tipo_inventario,
            'id_unidad' => $ConsultaGlobal->id_unidad,
            'id_marca' => $ConsultaGlobal->id_marca,
            'glosa_marca' => $ConsultaGlobal->glosa_marca,
            "id_tipo_afectacion" => $ConsultaGlobal->id_tipo_afectacion,
            'codigo_producto' => $ConsultaGlobal->codigo_producto,
            'glosa_producto' => $ConsultaGlobal->glosa_producto,
            'codigo_barra_producto' => $ConsultaGlobal->codigo_barra_producto,
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
            "producto_relacionado" => $ConsultaGlobal->producto_relacionado,
            "tipoAfectacion" => $tipoAfectacion,
            "stock_producto_bodega" => $stock_producto_bodega
        ];
        return $fillable;
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

    public function traerAfectacion()
    {
        echo TipoAfectacion::where('vigente_afectacion', 1)->get();
    }

    public function traerBodegaStock()
    {
        $stockbodega = StockProductoBodega::join('bodega', 'bodega.id_bodega', 'stock_producto_bodega.id_bodega')
            ->where('id_producto', $_POST['id_producto'])->get();
        $imageproducto = ProductoImagen::where('id_producto', $_POST['id_producto'])->where('portada_producto_imagen', 1)
            ->select('url_producto_imagen')
            ->first();
        $respuesta = [
            "stock_producto_bodega" => $stockbodega,
            'producto_imagen' => $imageproducto
        ];
        echo json_encode($respuesta);
    }

    public function filtrarProductoRelacionado()
    {
        $id_producto = json_decode($_GET['id_producto']);
        $searchTerm = $_GET['search']; // Asegúrate de validar y limpiar esta entrada de usuario.
        $marcas = Producto::Where(function ($query) use ($searchTerm) {
            $query->where('glosa_producto', 'LIKE', "%$searchTerm%")
                ->orWhere('codigo_producto', 'LIKE', "%$searchTerm%")
                ->orWhere('codigo_barra_producto', 'LIKE', "%$searchTerm%");
        })
            ->where('vigente_producto', 1)
            ->whereNotIn('id_producto', $id_producto)
            ->get()
            ->sortBy(function ($item) {
                return [
                    substr($item->glosa_producto, 0, 1),
                    substr($item->glosa_producto, -1)
                ];
            })
            ->values()
            ->toArray();
        echo json_encode($marcas);
    }

    public function traerProductoIdRelacionado()
    {
        $consulta = "SELECT null as id_producto_relacionado,id_producto,glosa_producto,codigo_producto,
         (
            select url_producto_imagen from producto_imagen
            where id_producto=producto.id_producto and
            portada_producto_imagen=1
            limit 1
         ) as path_producto_imagen
         FROM producto
         where id_producto={$_GET['id_producto']}";
        $producto = (new ConsultaGlobal())->ConsultaSingular($consulta);
        echo json_encode($producto);
    }
}
