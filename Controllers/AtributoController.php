<?php



require_once "models/Atributo.php";

class AtributoController
{

    public function ListaAtributo()
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
        $recordsFilteredTotal = Atributo::where("vigente_atributo", 1);
        if (!empty($buscar)) {
            $recordsFilteredTotal = $recordsFilteredTotal->Where(function ($query) use ($buscar) {
                $query->where('glosa_atributo', 'LIKE', "%$buscar%");
            });
        }
        $recordsFilteredTotal = $recordsFilteredTotal->get()->count();
        $listaProducto = Atributo::where("vigente_atributo", 1);
        if (!empty($buscar)) {
            $listaProducto = $listaProducto->Where(function ($query)  use ($buscar) {
                $query->where('glosa_atributo', 'LIKE', "%$buscar%");
            });
        }
        $listaProducto = $listaProducto->orderBy('id_atributo', 'desc')
        ->skip($DatosPost->start)
        ->take($longitud)
        ->get();
        $data = array();
        foreach ($listaProducto as $item) {
            $catpadre = "";
            if ($item['id_padre_atributo'] == 0) {
                $catpadre = "";
            } else {
                $atributo_padre = Atributo::where('id_atributo', $item['id_padre_atributo'])->first();
                $catpadre =   $atributo_padre['glosa_atributo'];
            }
            $element = [
                'id_atributo' => $item['id_atributo'],
                'glosa_atributo' => $item['glosa_atributo'],
                'atributo_padre' => $catpadre
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


    public function ListaAtributoDeshabilitado()
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

        $recordsFilteredTotal = Atributo::where("vigente_atributo", 0);
        if (!empty($buscar)) {
            //FILTRAO PARA BUSCAR EL DATATABLE
            $recordsFilteredTotal = $recordsFilteredTotal->Where(function ($query) use ($buscar) {
                $query->where('glosa_atributo', 'LIKE', "%$buscar%");
            });
        }
        $recordsFilteredTotal = $recordsFilteredTotal->get()->count();
        $listaProducto = Atributo::where("vigente_atributo", 0);
        if (!empty($buscar)) {
            //FILTRAO PARA BUSCAR EL DATATABLE
            $listaProducto = $listaProducto->Where(function ($query)  use ($buscar) {
                $query->where('glosa_atributo', 'LIKE', "%$buscar%");
            });
        }
        $listaProducto = $listaProducto->orderBy('id_atributo', 'desc')->skip($DatosPost->start)->take($longitud)->get();
        $data = array();
        foreach ($listaProducto as $item) {
            $catpadre = "";
            if ($item['id_padre_atributo'] == 0) {
                $catpadre = "";
            } else {
                $atributo_padre = Atributo::where('id_atributo', $item['id_padre_atributo'])->first();
                $catpadre =   $atributo_padre['glosa_atributo'];
            }
            $element = [
                'id_atributo' => $item['id_atributo'],
                'glosa_atributo' => $item['glosa_atributo'],
                'atributo_padre' => $catpadre
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

    public static function CargarAtributo()
    {
        $Atributo = Atributo::select('*')
            ->where("vigente_atributo", 1)
            ->get();
        if (count($Atributo) > 0) {
            $categoria = "";
            foreach ($Atributo as $categoria) {
                $matrizCategoria[$categoria->id_padre_atributo][] = $categoria;
            }
            $arbolCategoriaDinamico = static::ObtenerArbolCategoria($matrizCategoria);
            $categoria = ["atributo" => $arbolCategoriaDinamico];
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
                $nuevaCategoria->id_padre = $categoria['id_padre_atributo'];
                $nuevaCategoria->id_atributo = $categoria['id_atributo'];
                $nuevaCategoria->glosa_atributo = $categoria['glosa_atributo'];
                $nuevaCategoria->vigente_atributo = $categoria['vigente_atributo'];
                if (isset($matrizCategoria[$categoria['id_atributo']])) {
                    $nuevaCategoria->subatributo = self::ObtenerArbolCategoria($matrizCategoria, $categoria['id_atributo']); //funcion anidada que se usa recursivamente
                }
                $menu[] = $nuevaCategoria;
            }
            return $menu;
        }
    }

    public function GestionarAtributo()
    {
        $datos = [
            'glosa_atributo' => $_POST['glosa_atributo'],
            'descripcion_atributo' => $_POST['descripcion_atributo'],
            'vigente_atributo' => 1
        ];
        $atributo_padre = json_decode($_POST['atributo_padre']);
        if ($_POST['accion'] == "CREAR") {
            if (count($atributo_padre) > 0) {
                $datos += ['id_padre_atributo' => $atributo_padre[0]];
            } else {
                $datos += ['id_padre_atributo' => 0];
            }
            Atributo::create($datos);
            $respuesta = "Creado";
        } else {
            if (count($atributo_padre) > 0) {
                $datos += ['id_padre_atributo' => $atributo_padre[0]];
            } else {
                $datos += ['id_padre_atributo' => 0];
            }
            Atributo::where('id_atributo', $_POST['id_atributo'])->update($datos);
            $respuesta = "Actualizado";
        }
        echo json_encode($respuesta);
    }

    public function Habilitar_Deshabilitar_Categoria()
    {
        if ($_POST['accion'] == "activado") {
            $datos = ['vigente_atributo' => 1];
        } else {
            $datos = ['vigente_atributo' => 0];
        }
        Atributo::where("id_atributo", $_POST['id_atributo'])->update($datos);
    }

    public function TraerCategoria()
    {
        $categoria = Atributo::where("id_atributo", $_POST['id_atributo'])->first();
        echo $categoria;
    }
}
