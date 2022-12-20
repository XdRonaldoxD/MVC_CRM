<?php



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
                $cat_padre =   $atributo_padre['glosa_categoria'];
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
                $cat_padre =   $atributo_padre['glosa_categoria'];
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
        $urlAmigable = "";
        $urlAmigable .= str_replace(" ", "-",  $_POST['glosa_categoria']);
        $urlAmigable = str_replace("/", "-", $urlAmigable);
        $urlAmigable = str_replace("\\", "-", $urlAmigable);
        $urlAmigable = str_replace("+", "-", $urlAmigable);
        $datos = [
            'id_tipo_inventario' => $_POST['id_tipo_inventario'],
            'glosa_categoria' => $_POST['glosa_categoria'],
            'descripcion_categoria' => $_POST['descripcion_categoria'],
            'vigente_categoria' => 1,
            'visibleonline_categoria' => ($_POST['visibleOnline'] == "true") ? 1 : 0,
            'urlamigable_categoria'=>$urlAmigable
        ];

        if (!empty($_FILES['imagen'])) {
            $nombre_imagen = $_FILES['imagen']['name'];
            $temp = $_FILES['imagen']['tmp_name'];
            //crear el directorio
            if (!file_exists(__DIR__ . "/../archivo/imagen_categoria")) {
                mkdir(__DIR__ . "/../archivo/imagen_categoria", 0777, true);
            }

            if ($_POST['accion'] !== "CREAR") {
                $Categorias = Categorias::where('id_categoria', $_POST['id_categoria'])->first();
                if ($Categorias->pathimagen_categoria) {
                    unlink(__DIR__ . "/../archivo/imagen_categoria/$Categorias->pathimagen_categoria");
                }
            }

            // GUARDA LA IMAGEN
            $fechacreacion = date('Y-m-d H:i:s');
            $separaFecha = explode(" ", $fechacreacion);
            $Fecha = explode("-", $separaFecha[0]);
            $path = mt_srand(10) . "_" . $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . $nombre_imagen;
            move_uploaded_file($temp, __DIR__ . "/../archivo/imagen_categoria/$path");
            //
            $datos += ["pathimagen_categoria" => $path];
        }


        $categoria_padre = json_decode($_POST['categoria_padre']);
        if ($_POST['accion'] == "CREAR") {
            if (count($categoria_padre) > 0) {
                foreach ($categoria_padre as $key => $elementos) {
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
                foreach ($categoria_padre as $key => $elementos) {
                    $datos += ['id_categoria_padre' => $elementos];
                    Categorias::where('id_categoria', $_POST['id_categoria'])->update($datos);
                }
            } else {
                $datos += ['id_categoria_padre' => 0];
                Categorias::where('id_categoria', $_POST['id_categoria'])->update($datos);
            }
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
        if ($categoria->pathimagen_categoria) {
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
            $domain = $_SERVER['HTTP_HOST'];
            $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_categoria/$categoria->pathimagen_categoria";
            $categoria->pathimagen_categoria = $imagens;
        }
        echo $categoria;
    }


}