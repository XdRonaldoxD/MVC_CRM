<?php
require_once "models/Producto.php";
require_once "models/ProductoColor.php";
require_once "models/ProductoImagen.php";
require_once "models/ProductoRelacionado.php";
require_once "models/ProductoEspecificaciones.php";
require_once "models/AtributoProducto.php";
require_once "models/CategoriaProducto.php";
require_once "config/Helper.php";

class NuevoProductoController
{
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
        try {
            $Helper = new Helper();
            $imagenes_producto = json_decode($_POST['imagenes_producto']);
            $especificaciones = json_decode($_POST['especificaciones']);
            $producto_relacion = json_decode($_POST['producto_relacion']);
            $colores = json_decode($_POST['colores']);
            $informacionForm = json_decode($_POST['informacionForm']);
            $PrecioStockForm = json_decode($_POST['PrecioStockForm']);
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
         
            $DatosProducto = [
                'id_tipo_producto' => 1,
                'id_tipo_inventario' => $informacionForm->tipo_inventario,
                'codigo_producto' => $informacionForm->codigo_producto,
                'glosa_producto' => $informacionForm->glosa_producto,
                'detalle_producto' => $informacionForm->descripcion_corta,
                'detallelargo_producto' => $informacionForm->descripcion_extendida,
                'cantidad_producto' => $PrecioStockForm->stock,
                'stock_producto' => $PrecioStockForm->stock,
                'precioventa_producto' => $PrecioStockForm->precio_venta,
                'preciocosto_producto' => $PrecioStockForm->precio_costo,
                'urlamigable_producto'=>$urlAmigable,
                'fechacreacion_producto' => date('Y-m-d H:i:s'),
                'vigente_producto' => 1,
                'visibleonline_producto' => ($informacionForm->visible_tienda == true) ?  1 : 0
            ];

            if (isset($informacionForm->id_producto)) {

                $Productos = Producto::where('id_producto', $informacionForm->id_producto)->update($DatosProducto);
                ProductoColor::where('id_producto', $informacionForm->id_producto)->delete();
                ProductoImagen::where('id_producto', $informacionForm->id_producto)->delete();
                ProductoRelacionado::where('id_producto', $informacionForm->id_producto)->delete();
                ProductoEspecificaciones::where('id_producto', $informacionForm->id_producto)->delete();
                AtributoProducto::where('id_producto', $informacionForm->id_producto)->delete();
                CategoriaProducto::where('id_producto', $informacionForm->id_producto)->delete();
                $id_producto = $informacionForm->id_producto;
            } else {
              
                $Productos = Producto::create($DatosProducto);
                $id_producto = $Productos->id_producto;
            }
            foreach ($colores as $key => $element) {
                $colores = [
                    'id_producto' => $id_producto,
                    'nombre_producto_color' => $element->nombre_color,
                    'hexadecimal_producto_color' => $element->color_hexadecimal,
                ];
                ProductoColor::create($colores);
            }
            foreach ($imagenes_producto as $key => $archivos) {
                //DECODIFICA LA IMAGEN BASE64
                $Base64Img = $archivos->imagen;
                list(, $Base64Img) = explode(';', $Base64Img);
                list(, $Base64Img) = explode(',', $Base64Img);
                $Base64Img = base64_decode($Base64Img);
                $extension = $Helper->getImageMimeType($Base64Img);
                //crear el directorio
                if (!file_exists(__DIR__ . "/../archivo/imagen_producto")) {
                    mkdir(__DIR__ . "/../archivo/imagen_producto", 0777, true);
                }
                // GUARDA LA IMAGEN
                $fechacreacion = date('Y-m-d H:i:s');
                $separaFecha = explode(" ", $fechacreacion);
                $Fecha = explode("-", $separaFecha[0]);
                $path = $archivos->nombre_imagen . mt_srand(10) . "_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() .$key. "_.$extension";
                $file = fopen(__DIR__ . "/../archivo/imagen_producto/$path", "wb");
                fwrite($file, $Base64Img);
                fclose($file);
                // print_r($archivos);
                // die;
                $ProductoImagen = array(
                    "id_producto" =>  $id_producto,
                    "nombre_producto_imagen" => $archivos->nombre_imagen,
                    "extension_producto_imagen" => "$extension",
                    "peso_producto_imagen" => filesize(__DIR__ . "/../archivo/imagen_producto/$path"),
                    "path_producto_imagen" => $path,
                    "fechacreacion_producto_imagen" => date('Y-m-d H:i:s'),
                    'orden_producto_imagen' => $archivos->orden_imagen,
                    "estado_producto_imagen" => 1,
                    "portada_producto_imagen" => $archivos->portada
                );
                ProductoImagen::create($ProductoImagen);
            }
            foreach ($producto_relacion as $key => $elementos) {
                $datos = [
                    'id_producto' => $elementos->id_producto,
                    'idproductopadre_producto_relacionado' => $id_producto,
                    'order_producto_relacionado' => $key + 1,
                    'vigente_producto_relacionado' => 1
                ];
                ProductoRelacionado::Create($datos);
            }
            foreach ($especificaciones as $key => $elementos) {

                $datos = [
                    'id_producto' => $id_producto,
                    'glosa_especificaciones_producto' => $elementos->nombre,
                    'respuesta_especificaciones_producto' => $elementos->descripcion
                ];
                ProductoEspecificaciones::create($datos);
            }
            foreach ($atributo_seleccionado as $key => $elementos) {
                $datos = [
                    'id_atributo' => $elementos->id_atributo,
                    'id_producto' => $id_producto,
                    'stock_atributo' => $elementos->cantidad
                ];
                AtributoProducto::create($datos);
            }
            foreach ($valorescategoria as $key => $elementos) {
                $datos = [
                    "id_categoria" => $elementos,
                    'id_producto' => $id_producto,
                ];
                CategoriaProducto::create($datos);
            }
            echo json_encode("Se Guardo exitosamente el producto");
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
