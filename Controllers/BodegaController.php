<?php
require_once "models/ConsultaGlobal.php";
require_once "models/Bodega.php";
class BodegaController
{
    public function traerBodega()
    {
        return Bodega::where('id_bodega', $_POST['id_bodega'])->first();
    }
    public function gestionarBodega()
    {
        $bodega = json_decode($_POST['bodega']);
        $fillable = [
            'glosa_bodega' => $bodega->glosa_bodega,
        ];
        if ($bodega->id_bodega) {
            Bodega::where('id_bodega', $bodega->id_bodega)->update($fillable);
            $respuesta = "Bodega Actualizado Exitosamente";
        } else {
            $fillable += [
                'vigente_bodega' => 1
            ];
            $bodega = Bodega::create($fillable);
            $fillable += [
                "id_bodega" => $bodega->id_bodega
            ];
            $respuesta = "Bodega Creado Exitosamente";
        }
        $respuesta = [
            "datos" => $fillable,
            "respuesta" => $respuesta
        ];
        echo json_encode($respuesta);
    }

    public function gestionarestadoBodega()
    {
        if ($_POST['accion'] == "activar") {
            $estado = [
                'vigente_bodega' => $_POST['vigente_bodega']
            ];
        } else {
            $estado = [
                'vigente_bodega' => $_POST['vigente_bodega']
            ];
        }
        Bodega::where('id_bodega', $_POST['id_bodega'])->update($estado);
        echo json_encode("Actualizado Estado.");
    }

    public function listarBodegas()
    {
        $datosPost = file_get_contents("php://input");
        $datosPost = json_decode($datosPost);
        if ($datosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $datosPost->length;
        }
        $buscar = $datosPost->search->value;
        $consulta = " and (glosa_bodega LIKE '%$buscar%') ";
        $query = "SELECT * FROM bodega
        WHERE  vigente_bodega=$datosPost->vigente_bodega $consulta
        order by bodega.id_bodega desc";
        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $datosPost->start ";
        $consultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $datosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $consultaGlobal
        );
        echo json_encode($datos);
    }
}
