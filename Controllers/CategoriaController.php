<?php

use BenMajor\ImageResize\Image;
use Verot\Upload\Upload;

require_once "models/Categorias.php";
require_once "models/TipoInventario.php";

class CategoriaController
{

    public function TraerTipoInventario()
    {
        $tipo_inventario = TipoInventario::where("vigente_tipo_inventario", 1)->get();
        echo $tipo_inventario;
    }
    public function ListaCategoria()
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
        $recordsFilteredTotal = Categorias::where("vigente_categoria", 1);
        if (!empty($buscar)) {
            $recordsFilteredTotal = $recordsFilteredTotal->Where(function ($query) use ($buscar) {
                $query->where('glosa_categoria', 'LIKE', "%$buscar%");
            });
        }
        $recordsFilteredTotal = $recordsFilteredTotal->get()->count();
        $listaProducto = Categorias::where("vigente_categoria", 1);
        if (!empty($buscar)) {
            $listaProducto = $listaProducto->Where(function ($query)  use ($buscar) {
                $query->where('glosa_categoria', 'LIKE', "%$buscar%");
            });
        }
        $listaProducto = $listaProducto->orderBy('id_categoria', 'desc')->skip($DatosPost->start)->take($longitud)->get();
        $data = array();
        foreach ($listaProducto as $item) {
            $cat_padre = "";
            if ($item['id_categoria_padre'] == 0) {
                $cat_padre = "";
            } else {
                $atributo_padre = Categorias::where('id_categoria', $item['id_categoria_padre'])->first();
                $cat_padre =   $atributo_padre['glosa_categoria'] ?? '';
            }
            $element = [
                'id_categoria' => $item['id_categoria'],
                'glosa_categoria' => $item['glosa_categoria'],
                'atributo_padre' => $cat_padre
            ];
            array_push($data, $element);
        }
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => $recordsFilteredTotal,
            "recordsFiltered" => $recordsFilteredTotal,
            "data" => $data
        );
        echo json_encode($datos);
    }


    public function ListaCategoriaDeshabilitado()
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

        $recordsFilteredTotal = Categorias::where("vigente_categoria", 0);
        if (!empty($buscar)) {
            //FILTRAO PARA BUSCAR EL DATATABLE
            $recordsFilteredTotal = $recordsFilteredTotal->Where(function ($query) use ($buscar) {
                $query->where('glosa_categoria', 'LIKE', "%$buscar%");
            });
        }
        $recordsFilteredTotal = $recordsFilteredTotal->get()->count();
        $listaProducto = Categorias::where("vigente_categoria", 0);
        if (!empty($buscar)) {
            //FILTRAO PARA BUSCAR EL DATATABLE
            $listaProducto = $listaProducto->Where(function ($query)  use ($buscar) {
                $query->where('glosa_categoria', 'LIKE', "%$buscar%");
            });
        }
        $listaProducto = $listaProducto->orderBy('id_categoria', 'desc')->skip($DatosPost->start)->take($longitud)->get();
        $data = array();
        foreach ($listaProducto as $item) {
            $cat_padre = "";
            if ($item['id_categoria_padre'] == 0) {
                $cat_padre = "";
            } else {
                $atributo_padre = Categorias::where('id_categoria', $item['id_categoria_padre'])->first();
                $cat_padre =   $atributo_padre['glosa_categoria'] ?? '';
            }
            $element = [
                'id_categoria' => $item['id_categoria'],
                'glosa_categoria' => $item['glosa_categoria'],
                'atributo_padre' => $cat_padre
            ];
            array_push($data, $element);
        }
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => $recordsFilteredTotal,
            "recordsFiltered" => $recordsFilteredTotal,
            "data" => $data
        );
        echo json_encode($datos);
    }

    public static function CargarCategoria()
    {
        $categorias = Categorias::select('*')
            ->where('id_tipo_inventario', $_POST['id_tipo_inventario'])
            ->where("vigente_categoria", 1)
            ->get();
        if (count($categorias) > 0) {
            $categoria = "";
            foreach ($categorias as $categoria) {
                $matrizCategoria[$categoria->id_categoria_padre][] = $categoria;
            }
            $arbolCategoriaDinamico = static::ObtenerArbolCategoria($matrizCategoria);
            $categoria = ["categoria" => $arbolCategoriaDinamico];
            echo json_encode($categoria);
        } else {
            $cat = "";
            echo json_encode($cat);
        }
    }
    public static function  ObtenerArbolCategoria($matrizCategoria, $padre = 0)
    {
        if ($matrizCategoria) {
            $menu = [];
            foreach ($matrizCategoria[$padre] as $categoria) {
                $nuevaCategoria = new \stdClass();
                $nuevaCategoria->id_padre = $categoria['id_categoria_padre'];
                $nuevaCategoria->id_categoria = $categoria['id_categoria'];
                $nuevaCategoria->glosa_categoria = $categoria['glosa_categoria'];
                $nuevaCategoria->vigente_categoria = $categoria['vigente_categoria'];
                if (isset($matrizCategoria[$categoria['id_categoria']])) {
                    $nuevaCategoria->subcategoria = self::ObtenerArbolCategoria($matrizCategoria, $categoria['id_categoria']); //funcion anidada que se usa recursivamente
                }
                $menu[] = $nuevaCategoria;
            }
            return $menu;
        }
    }

    public function GestionarCategoria()
    {
        Cloudinary::config([
            'cloud_name' => cloud_name,
            'api_key'    => api_key,
            'api_secret' => api_secret,
            "secure" => true
        ]);
        $formulario = json_decode($_POST['formulario']);
        $folder = $_SERVER['SERVER_NAME'] . '/archivo/' . DOMINIO_ARCHIVO . '/imagen_categoria';

        $urlAmigable = "";
        $urlAmigable .= str_replace(" ", "-",  $formulario->glosa_categoria);
        $urlAmigable = str_replace("/", "-", $urlAmigable);
        $urlAmigable = str_replace("\\", "-", $urlAmigable);
        $urlAmigable = str_replace("+", "-", $urlAmigable);
        $datos = [
            'id_tipo_inventario' => $formulario->id_tipo_inventario,
            'glosa_categoria' => $formulario->glosa_categoria,
            'descripcion_categoria' => $formulario->descripcion_categoria ?? null,
            'visibleonline_categoria' => ($formulario->visibleOnline) ? 1 : 0,
            'urlamigable_categoria' => $urlAmigable . '-' . $formulario->codigo_categoria,
            'codigo_categoria' => $formulario->codigo_categoria,
        ];
        if (!empty($_FILES['imagen'])) {
            $imagen = $_FILES['imagen']['tmp_name'];
            $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
            $nombre_imagen = str_replace(' ', '', $nombre_imagen);
            if ($_POST['accion'] !== "CREAR") {
                $this->eliminarImagen($formulario->id_categoria, 'MegaMenu');
            }
            // TRANSFORMACIÓN DE IMAGEN A 370 * 230
            $transformacion = array(
                "width" => 370,
                "height" => 230
            );
            //LO SUBIMOS AL CLOUDINARY A LA NUBE PARA QUE NO SEA MAS PESADO EL SERVIDOR
            $respuesta = \Cloudinary\Uploader::upload($imagen, array(
                "folder" => $folder,
                "public_id" => $nombre_imagen . "_" . time(),  // Nombre único en Cloudinary
                "overwrite" => true,  // Sobrescribe si ya existe una imagen con el mismo nombre
                "resource_type" => "image",
                "transformation" => $transformacion
            ));
            $datos += [
                "pathimagen_categoria" => $respuesta['secure_url'],
                "pathimagen_id_categoria" => $respuesta['public_id'],
            ];
        }
        if (!empty($_FILES['imagen_popular'])) {
            $imagen_popular = $_FILES['imagen_popular']['tmp_name'];
            $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
            $nombre_imagen = str_replace(' ', '', $nombre_imagen);
   
            if ($_POST['accion'] !== "CREAR") {
                $this->eliminarImagen($formulario->id_categoria, 'Popular');
            }
            // TRANSFORMACIÓN DE IMAGEN A 370 * 230
            $transformacion = array(
                "width" => 700,
                "height" => 700
            );
            //LO SUBIMOS AL CLOUDINARY A LA NUBE PARA QUE NO SEA MAS PESADO EL SERVIDOR
            $respuesta = \Cloudinary\Uploader::upload($imagen_popular, array(
                "folder" => $folder,
                "public_id" => $nombre_imagen . "_" . time(),  // Nombre único en Cloudinary
                "overwrite" => true,  // Sobrescribe si ya existe una imagen con el mismo nombre
                "resource_type" => "image",
                "transformation" => $transformacion
            ));
            $datos += [
                "pathimagenpopular_categoria" => $respuesta['secure_url'],
                "pathimagenpopular_id_categoria" => $respuesta['public_id'],
            ];
        }



        $categoria_padre = json_decode($_POST['categoria_padre']);
        if ($_POST['accion'] == "CREAR") {
            $datos += [
                'vigente_categoria' => 1
            ];
            if (count($categoria_padre) > 0) {
                foreach ($categoria_padre as $elementos) {
                    $datos += ['id_categoria_padre' => $elementos];
                    Categorias::create($datos);
                }
            } else {
                $datos += ['id_categoria_padre' => 0];
                Categorias::create($datos);
            }
            $respuesta = "Creado";
        } else {
            if (count($categoria_padre) > 0) {
                foreach ($categoria_padre as $elementos) {
                    $datos += ['id_categoria_padre' => $elementos];
                }
            }
            Categorias::where('id_categoria', $formulario->id_categoria)->update($datos);
            $respuesta = "Actualizado";
        }
        echo json_encode($respuesta);
    }

    public function Habilitar_Deshabilitar_Categoria()
    {
        if ($_POST['accion'] == "activado") {
            $datos = ['vigente_categoria' => 1];
        } else {
            $datos = ['vigente_categoria' => 0];
        }
        Categorias::where("id_categoria", $_POST['id_categoria'])->update($datos);
    }

    public function TraerCategoria()
    {
        $categoria = Categorias::where("id_categoria", $_POST['id_categoria'])->first();
        echo $categoria;
    }

    public function FiltrarCategoria()
    {
        $buscar = $_GET['search'];
        $respuesta = Categorias::where('glosa_categoria', 'LIKE', "%$buscar%")->get();
        echo $respuesta;
    }

    public function eliminarImagen($id_categoria, $columnas)
    {
        $folder = $_SERVER['SERVER_NAME'] . '/archivo/' . DOMINIO_ARCHIVO . '/imagen_categoria';
        $categoria = Categorias::where('id_categoria', $id_categoria)->first();
        switch ($columnas) {
            case 'MegaMenu':
                $url = $categoria->pathimagen_categoria;
                $idurl = $categoria->pathimagen_id_categoria;
                break;
            default:
                $url = $categoria->pathimagenpopular_categoria;
                $idurl = $categoria->pathimagenpopular_id_categoria;
                break;
        }
   
        if (!$idurl && $url) {
            $search = new \Cloudinary\Search;
            $search_result = $search->expression("filename:" . basename($url))->execute();
            $public_id = $search_result["resources"][0]["public_id"];
        } else {
            $public_id = $idurl;
        }
        //ELIMINAMOS LA IMAGEN
        if ($public_id) {
            \Cloudinary\Uploader::destroy($public_id, [
                "folder" =>$folder
            ]);
        }
    
    }
}
