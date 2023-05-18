<?php
require_once "models/ConsultaGlobal.php";
require_once "models/Marca.php";


class MarcaController
{
    public function ListarMarca()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;
        $consulta = " and (glosa_marca LIKE '%$buscar%') ";
        $query = "SELECT * FROM marca
        WHERE  vigente_marca=1 $consulta
        order by marca.id_marca desc";
        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $DatosPost->start ";
        $ConsultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $ConsultaGlobal
        );
        echo json_encode($datos);
    }
    public function listarMarcaDesactivados()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;
        $consulta = " and (glosa_marca LIKE '%$buscar%') ";
        $query = "SELECT * FROM marca
        WHERE  vigente_marca=0 $consulta 
        order by marca.id_marca desc";
        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $DatosPost->start ";
        $ConsultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $ConsultaGlobal
        );
        echo json_encode($datos);
    }

    public function gestionarMarca()
    {
        $datos = [
            'glosa_marca' => $_POST['glosa_marca'],
            'vigente_marca' => 1
        ];
        if ($_POST['accion'] == "CREAR") {
            $datos += ['vigente_marca' => 1];
            Marca::create($datos);
            $respuesta = "Creado";
        } else {
            Marca::where('id_marca', $_POST['id_marca'])->update($datos);
            $respuesta = "Actualizado";
        }
        echo json_encode($respuesta);
    }
    public function actualizarMarca()
    {
        if ($_POST['accion'] == "activado") {
            $datos = ['vigente_marca' => 1];
        } else {
            $datos = ['vigente_marca' => 0];
        }
        Marca::where("id_marca", $_POST['id_marca'])->update($datos);
    }

    public function traerMarca()
    {
        $marca = Marca::where('id_marca', $_POST['id_marca'])->get();
        echo $marca;
    }
}
