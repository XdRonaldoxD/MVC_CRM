<?php

require_once "models/ConsultaGlobal.php";
require_once "models/Promocion.php";
class PromocionesController
{
    private $fechactual;
    public function __construct()
    {
        Cloudinary::config([
            'cloud_name' => cloud_name,
            'api_key'    => api_key,
            'api_secret' => api_secret,
            "secure" => true
        ]);
        $this->fechactual = date('Y-m-d H:i:s');
    }

    public function gestionarPromociones()
    {
        $request = json_decode($_POST['formulario']);
        $datos = [
            "titulo_promocion" => $request->titulo_promocion,
            "fecha_promocion" => $request->fecha_promocion,
            "descripcion_promocion" => $request->descripcion_promocion
        ];
        if (isset($_FILES['imagen_promocion']) && !empty($_FILES['imagen_promocion'])) {
            $imagen = $_FILES['imagen_promocion']['tmp_name'];
            $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
            if ($request->accion !== "CREAR") {
                $this->eliminarImagen($request->id_promocion);
            }
            // TRANSFORMACIÓN DE IMAGEN A 730 * 490
            $transformacion = array(
                "width" => 730,
                "height" => 490
            );
            //LO SUBIMOS AL CLOUDINARY A LA NUBE PARA QUE NO SEA MAS PESADO EL SERVIDOR
            $respuesta = \Cloudinary\Uploader::upload($imagen, array(
                "folder" => $_SERVER['SERVER_NAME'] . '/archivo/imagen_promocion',
                "public_id" => $nombre_imagen . "_" . time(),  // Nombre único en Cloudinary
                "overwrite" => true,  // Sobrescribe si ya existe una imagen con el mismo nombre
                "resource_type" => "image",
                "transformation" => $transformacion
            ));
            $datos += [
                "url_promocion" => $respuesta['secure_url'],
                "id_url_promocion" => $respuesta['public_id'],
            ];
        }
        if ($request->accion === "CREAR") {
            $datos += [
                "fecha_creacion_promocion" => $this->fechactual
            ];
            Promocion::create($datos);
        } else {
            Promocion::where('id_promocion', $request->id_promocion)->update($datos);
        }
        echo json_encode("Promocion Gestionado Exitosamente");
    }

    public function eliminarPromocion()
    {
        $promocion = Promocion::where('id_promocion', $_POST['id_promocion'])->first();
        $this->eliminarImagen($_POST['id_promocion']);
        $promocion->delete();
        echo json_encode("Eliminado Exitosamente");
    }

    public function eliminarImagen($id_promocion)
    {
        $promocion = Promocion::where('id_promocion', $id_promocion)->first();
        if (!$promocion->id_url_promocion) {
            $search = new \Cloudinary\Search;
            $search_result = $search->expression("filename:" . basename($promocion->url_promocion))->execute();
            $public_id_promocion = $search_result["resources"][0]["public_id"];
        } else {
            $public_id_promocion = $promocion->id_url_promocion;
        }
        //ELIMINAMOS LA IMAGEN
        \Cloudinary\Uploader::destroy($public_id_promocion, [
            "folder" => $_SERVER['SERVER_NAME'] . '/archivo/imagen_producto'
        ]);
    }

    public function listarPromociones()
    {
        $datosPost = file_get_contents("php://input");
        $datosPost = json_decode($datosPost);
        if ($datosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $datosPost->length;
        }
        $buscar = $datosPost->search->value;
        $consulta = " where (titulo_promocion LIKE '%$buscar%'
        or descripcion_promocion LIKE '%$buscar%'
        ) ";
        $query = "SELECT * FROM promocion
        $consulta
        order by id_promocion desc";
        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $datosPost->start ";
        $consultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        foreach ($consultaGlobal as  &$element) {
            $fechactual = strtotime(date('Y-m-d'));
            $fechatermino = strtotime($element->fecha_promocion);
            $estado = 1;
            if ($fechactual > $fechatermino) {
                $estado = 0;
            }
            $element->estado = $estado;
        }
        $datos = array(
            "draw" => $datosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $consultaGlobal
        );
        echo json_encode($datos);
    }
}
