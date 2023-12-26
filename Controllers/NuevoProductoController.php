<?php
require_once "models/Producto.php";
require_once "models/ProductoColor.php";
require_once "models/ProductoImagen.php";
require_once "models/ProductoRelacionado.php";
require_once "models/ProductoEspecificaciones.php";
require_once "models/AtributoProducto.php";
require_once "models/CategoriaProducto.php";
require_once "models/Marca.php";
require_once "models/StockProductoBodega.php";
require_once "config/Helper.php";
require_once "config/Parametros.php";
require_once "Controllers/ProductoController.php";


class NuevoProductoController
{

    public function filtrarMarca()
    {
        $searchTerm = $_GET['search']; // Asegúrate de validar y limpiar esta entrada de usuario.
        $marcas = Marca::where('glosa_marca', 'like', '%' . $searchTerm . '%')
            ->where('vigente_marca', 1)
            ->get()
            ->sortBy(function ($item) {
                return [
                    substr($item->glosa_marca, 0, 1),
                    substr($item->glosa_marca, -1)
                ];
            })
            ->values()
            ->toArray();
        echo json_encode($marcas);
    }
    public function ListaProductosRelacionado()
    {
        // echo json_encode($_POST['id_producto']);
        $id_producto_general = explode(',', $_POST['id_producto']);
        $Todos_Producto = Producto::join('producto_imagen', 'producto_imagen.id_producto', 'producto.id_producto')
            ->where('producto.vigente_producto', 1)
            ->where('producto_imagen.portada_producto_imagen', 1);
        if (isset($_POST['traendo_id'])) {
            $Todos_Producto = $Todos_Producto->where("producto.id_producto", $_POST['id_producto']);
        } else {
            $Todos_Producto = $Todos_Producto->whereNotIn("producto.id_producto", $id_producto_general);
        }
        $Todos_Producto = $Todos_Producto->select("producto.*", "producto_imagen.path_producto_imagen");
        if (isset($_POST['traendo_id'])) {
            $Todos_Producto = $Todos_Producto->first();
        } else {
            $Todos_Producto = $Todos_Producto->get();
            $Todos_Producto = $Todos_Producto->toArray();
        }
        echo json_encode($Todos_Producto);
    }

    public function GuardarProductoActualizar()
    {
        Cloudinary::config([
            'cloud_name' => cloud_name,
            'api_key'    => api_key,
            'api_secret' => api_secret,
            "secure" => true
        ]);
        try {
            $Helper = new Helper();
            $imagenes_producto = json_decode($_POST['imagenes_producto']);
            $especificaciones = json_decode($_POST['especificaciones']);
            $producto_relacion = json_decode($_POST['producto_relacion']);
            $colores = json_decode($_POST['colores']);
            $informacionForm = json_decode($_POST['informacionForm']);
            $precioStockForm = json_decode($_POST['PrecioStockForm']);
            $atributo_seleccionado = json_decode($_POST['atributo_seleccionado']);
            $valorescategoria = json_decode($_POST['valorescategoria']);

            $urlAmigable = "";
            if ($informacionForm->glosa_producto != "") {
                $urlAmigable .= str_replace(" ", "-", $informacionForm->glosa_producto) . "-";
            }
            $urlAmigable .= str_replace(" ", "-", $informacionForm->codigo_producto);
            $urlAmigable = str_replace("/", "-", $urlAmigable);
            $urlAmigable = str_replace("\\", "-", $urlAmigable);
            $urlAmigable = str_replace("+", "-", $urlAmigable);

            $datosProducto = [
                'id_tipo_producto' => 1,
                'id_tipo_inventario' => $informacionForm->tipo_inventario,
                'codigo_producto' => $informacionForm->codigo_producto,
                'glosa_producto' => $informacionForm->glosa_producto,
                'detalle_producto' => $informacionForm->descripcion_corta,
                'detallelargo_producto' => $informacionForm->descripcion_extendida,
                'precioventa_producto' => $precioStockForm->precio_venta,
                'id_unidad' => empty($informacionForm->id_unidad) ? null : $informacionForm->id_unidad ,
                'id_tipo_concentracion' => empty($informacionForm->id_tipo_concentracion) ? null : $informacionForm->id_tipo_concentracion,
                'urlamigable_producto' => $urlAmigable,
                'fechacreacion_producto' => date('Y-m-d H:i:s'),
                'vigente_producto' => 1,
                'visibleonline_producto' => ($informacionForm->visible_tienda) ?  1 : 0,
                'id_tipo_afectacion' => $informacionForm->id_tipo_afectacion,
                'id_marca' => $informacionForm->id_marca
            ];
            if (isset($informacionForm->id_producto)) {
                $Productos = Producto::where('id_producto', $informacionForm->id_producto)->update($datosProducto);
                $id_producto = $informacionForm->id_producto;
            } else {
                $Productos = Producto::create($datosProducto);
                $id_producto = $Productos->id_producto;
                foreach ($precioStockForm->stock as $key => $valor) {
                    $datostock=[
                        'id_producto'=>$id_producto,
                        'id_bodega'=>$valor->id_bodega,
                        'total_stock_producto_bodega'=>$valor->total_stock_producto_bodega,
                        'ultimopreciocompra_stock_producto_bodega'=>$valor->ultimopreciocompra_stock_producto_bodega,
                    ];
                    StockProductoBodega::create($datostock);
                }
            }
            //COLORES-----------------------------------------------------------
            $idproductoscolor = array_column(array_filter($colores, function ($item) {
                return $item->id_producto_color != null;
            }), 'id_producto_color');
            $productoColor = ProductoColor::where('id_producto', $id_producto)->pluck('id_producto_color')->toArray();
            $eliminarproductocolor = array_diff($productoColor, $idproductoscolor); //LOS QUE NO EXISTE RELACIONADO
            ProductoColor::whereIn('id_producto_color', $eliminarproductocolor)->delete();
            foreach ($colores as $key => $element) {
                $colores = [
                    'id_producto' => $id_producto,
                    'nombre_producto_color' => $element->nombre_producto_color,
                    'hexadecimal_producto_color' => $element->hexadecimal_producto_color,
                ];
                if ($element->id_producto_color) {
                    ProductoColor::where('id_producto_color', $element->id_producto_color)->update($colores);
                } else {
                    ProductoColor::create($colores);
                }
            }
            // -------------------------------------------------------------------

            $ProductoImagen_existe = ProductoImagen::where('id_producto', $id_producto)->pluck('id_producto_imagen')->toArray();
            foreach ($imagenes_producto as $key => $archivos) {
                if (isset($archivos->id_producto_imagen)) {
                    $ProductoImagen = ProductoImagen::where('id_producto_imagen', $archivos->id_producto_imagen)->first();
                    $ver = array_search($archivos->id_producto_imagen, $ProductoImagen_existe);
                    unset($ProductoImagen_existe[$ver]);
                    $ProductoImagen->portada_producto_imagen = ($archivos->portada == true) ? 1 : 0;
                    $ProductoImagen->save();
                } else {
                    //DECODIFICA LA IMAGEN BASE64
                    $Base64Img = $archivos->imagen;
                    list(, $Base64Img) = explode(';', $Base64Img);
                    list(, $Base64Img) = explode(',', $Base64Img);
                    $Base64Img = base64_decode($Base64Img);
                    $extension = $Helper->getImageMimeType($Base64Img);// OBTIENE LA EXTENSIÓN DE LA IMAGEN
                    //LO SUBIMOS AL CLOUDINARY A LA NUBE PARA QUE NO SEA MAS PESADO EL SERVIDOR
                    $respuesta = \Cloudinary\Uploader::upload('data:image/' . $extension . ';base64,' . base64_encode($Base64Img), array(
                        "folder" => $_SERVER['SERVER_NAME'] . '/archivo/imagen_producto',
                        "public_id" => $archivos->nombre_imagen . "_" . time(),  // Nombre único en Cloudinary
                        "overwrite" => true,  // Sobrescribe si ya existe una imagen con el mismo nombre
                        "resource_type" => "image",
                    ));
                    //----------------------------------------------------------------------------
                    $ProductoImagen = array(
                        "id_producto" =>  $id_producto,
                        "nombre_producto_imagen" => $archivos->nombre_imagen,
                        "fechacreacion_producto_imagen" => date('Y-m-d H:i:s'),
                        'orden_producto_imagen' => $archivos->orden_imagen,
                        "estado_producto_imagen" => 1,
                        "portada_producto_imagen" => $archivos->portada,
                        'public_id_producto_imagen' => $respuesta['public_id'],
                        'url_producto_imagen' => $respuesta['secure_url']
                    );
                    ProductoImagen::create($ProductoImagen);
                }
            }
            //ELIMINAMOS LAS IMAGENES
            foreach ($ProductoImagen_existe as $key => $value) {
                $productoImagen = ProductoImagen::where('id_producto_imagen', $value)->first();
                if (!$productoImagen->public_id_producto_imagen) {
                    $search = new \Cloudinary\Search;
                    $search_result = $search->expression("filename:" . basename($productoImagen->url_producto_imagen))->execute();
                    $public_id_producto_imagen = $search_result["resources"][0]["public_id"];
                }else{
                    $public_id_producto_imagen=$productoImagen->public_id_producto_imagen;
                }
                $respuesta = \Cloudinary\Uploader::destroy($public_id_producto_imagen, [
                    "folder" => $_SERVER['SERVER_NAME'] . '/archivo/imagen_producto'
                ]);
                $productoImagen->delete();
            }
            //PRODUCTO RELACIONADO
            $idproductos = array_column($producto_relacion, 'id_producto');
            $productorelacionado = ProductoRelacionado::where('idproductopadre_producto_relacionado', $id_producto)->pluck('id_producto')->toArray();
            $eliminarproductorelacion = array_diff($productorelacionado, $idproductos);
            ProductoRelacionado::whereIn('id_producto', $eliminarproductorelacion)->where('idproductopadre_producto_relacionado', $id_producto)->delete();
            foreach ($producto_relacion as $key => $elementos) {
                $datos = [
                    'id_producto' => $elementos->id_producto,
                    'idproductopadre_producto_relacionado' => $id_producto,
                    'order_producto_relacionado' => $key + 1
                ];
                if ($elementos->id_producto_relacionado) {
                    ProductoRelacionado::where('id_producto_relacionado', $elementos->id_producto_relacionado)->update($datos);
                } else {
                    $datos += [
                        'vigente_producto_relacionado' => 1
                    ];
                    ProductoRelacionado::create($datos);
                }
            }
            //--------------------------------------------------------------------------------------
            //ESPECIFICACIONES---------------------------------------------------------------------
            $idespeficicaciones = array_column(array_filter($especificaciones, function ($item) {
                return $item->id_especificaciones_producto !== null;
            }), 'id_especificaciones_producto');
            $productoespecificacion = ProductoEspecificaciones::where('id_producto', $id_producto)->pluck('id_especificaciones_producto')->toArray();
            $eliminarespecificacion = array_diff($productoespecificacion, $idespeficicaciones);
            ProductoEspecificaciones::whereIn('id_especificaciones_producto', $eliminarespecificacion)->delete();
            foreach ($especificaciones as $key => $elementos) {
                $datos = [
                    'id_producto' => $id_producto,
                    'glosa_especificaciones_producto' => $elementos->glosa_especificaciones_producto,
                    'respuesta_especificaciones_producto' => $elementos->respuesta_especificaciones_producto,
                ];
                if ($elementos->id_especificaciones_producto) {
                    ProductoEspecificaciones::where('id_especificaciones_producto', $elementos->id_especificaciones_producto)->update($datos);
                } else {
                    $datos += [
                        'vigente_especificaciones_producto' => 1
                    ];
                    ProductoEspecificaciones::create($datos);
                }
            }
            //ATRIBUTO---------------------------------------------------------------------------------
            $atributoproducto = AtributoProducto::where('id_producto', $id_producto)->pluck('id_atributo')->toArray();
            $eliminarAtributos = array_diff($atributoproducto, $atributo_seleccionado);
            AtributoProducto::whereIn('id_atributo', $eliminarAtributos)->where('id_producto', $id_producto)->delete();
            $agregarAtributos = array_diff($atributo_seleccionado, $atributoproducto);
            foreach ($agregarAtributos as $key => $elementos) {
                $datos = [
                    'id_atributo' => $elementos,
                    'id_producto' => $id_producto,
                    'stock_atributo' => $elementos->cantidad
                ];
                AtributoProducto::create($datos);
            }
            //CATEGORIA-------------------------------------------------------------------------------------------------
            $categorias = CategoriaProducto::where('id_producto', $id_producto)->pluck('id_categoria')->toArray();
            $eliminarCategorias = array_diff($categorias, $valorescategoria);
            CategoriaProducto::whereIn('id_categoria', $eliminarCategorias)->where('id_producto', $id_producto)->delete();
            $agregarCategorias = array_diff($valorescategoria, $categorias);
            foreach ($agregarCategorias as $element) {
                $datos = [
                    "id_categoria" => $element,
                    'id_producto' => $id_producto,
                ];
                CategoriaProducto::create($datos);
            }
            $consulta = " WHERE id_producto=$id_producto";
            $datos = (new ProductoController())->ObtenerDatosProducto($consulta);
            //---------------------------------------------------------------------------------------------------------------
            echo json_encode($datos);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
