<?php
require_once "models/Marca.php";
class MarcaController
{
    public function ListarMarca()
    {
        $marca = Marca::where('vigente_marca', 1)->get();
        echo $marca;
    }
    public function ListarMarcaDesactivados()
    {
        $marca = Marca::where('vigente_marca', 0)->get();
        echo $marca;
    }

    public function GestionarMarca()
    {
        $datos = [
            'glosa_marca' => $_POST['glosa_marca'],
            'descripcion_atributo' => $_POST['descripcion_atributo'],
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
    public function ActualizarMarca()
    {
        if ($_POST['accion'] == "activado") {
            $datos = ['vigente_marca' => 1];
        } else {
            $datos = ['vigente_marca' => 0];
        }
        Marca::where("id_marca", $_POST['id_marca'])->update($datos);
    }

    public function TraerMarca()
    {
        $marca = Marca::where('id_marca', $_POST['id_marca'])->get();
        echo $marca;
    }
}
