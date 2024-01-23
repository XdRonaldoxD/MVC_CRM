<?php

require_once "models/Slider.php";
require_once "models/ConsultaGlobal.php";

class SliderController
{

    public function ActualizarCrearSlider()
    {
        try {
            $formulario = json_decode($_POST['formulario']);
            $sliderdata = [
                'nombre_slider' => $formulario->titulo_slider,
                'id_categoria' => $formulario->id_categoria,
                'texto_slider'=>$formulario->texto_slider
            ];
            $rutacarpeta="/../archivo/".DOMINIO_ARCHIVO."/imagen_slider/";
            if (!file_exists(__DIR__ . $rutacarpeta)) {
                mkdir(__DIR__ . $rutacarpeta, 0777, true);
            }
            if ($formulario->id_slider) {
                $slider = Slider::where('id_slider', $formulario->id_slider)->first();
            }
            if (isset($_FILES['imagen_escritorio']) && !empty($_FILES['imagen_escritorio'])) {
                $imagen = $_FILES['imagen_escritorio']['name'];
                $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
                $nombre_imagen = preg_replace('([^A-Za-z0-9])', '', $nombre_imagen);
                $temp = $_FILES['imagen_escritorio']['tmp_name'];
                if (isset($slider) && $slider->pathescritorio_slider && file_exists(__DIR__ . $rutacarpeta.$slider->pathescritorio_slider)) {
                        unlink(__DIR__ . $rutacarpeta.$slider->pathescritorio_slider);
                }
                // GUARDA LA IMAGEN
                $path = time() . $nombre_imagen;
                $pathescritorio_slider =  $path . '.' . $ext;
                move_uploaded_file($temp, __DIR__ . $rutacarpeta.$pathescritorio_slider);
                $sliderdata += [
                    'pathescritorio_slider' => $pathescritorio_slider,
                ];
            }
            if (isset($_FILES['imagen_mobile']) && !empty($_FILES['imagen_mobile'])) {
                $imagen = $_FILES['imagen_mobile']['name'];
                $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
                $nombre_imagen = preg_replace('([^A-Za-z0-9])', '', $nombre_imagen);
                $temp = $_FILES['imagen_mobile']['tmp_name'];
                if (isset($slider) && $slider->pathmobile_slider && file_exists(__DIR__ . $rutacarpeta.$slider->pathmobile_slider)) {
                        unlink(__DIR__ . $rutacarpeta.$slider->pathmobile_slider);
                }
                // GUARDA LA IMAGEN
                $path = time() . $nombre_imagen;
                $pathmobile_slider =  $path . '.' . $ext;
                move_uploaded_file($temp, __DIR__ . $rutacarpeta.$pathmobile_slider);
                $pathmobile_slider =  $path . '.' . $ext;
                $sliderdata += [
                    'pathmobile_slider' => $pathmobile_slider,
                ];
            }
            if ($formulario->accion === "ACTUALIZAR") {
                Slider::where('id_slider', $formulario->id_slider)->update($sliderdata);
                $respuesta = "Actualizado";
            } else {
                $sliderdata += [
                    'fechacreacion_slider' => date('Y-m-d H:i:s'),
                    'vigente_slider' => 1,
                ];
                Slider::create($sliderdata);
                $respuesta = "Creado";
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
        }
        echo json_encode($respuesta);
    }

    public function GestionActivoDesactivado()
    {
        $accion = $_POST['accion'] ?? '';
        $idSlider = $_POST['id_slider'] ?? '';
        Slider::where('id_slider', $idSlider)->update(['vigente_slider' => ($accion === 'ACTIVAR' ? 1 : 0)]);
        echo json_encode("exitoso");
    }
    public function ListarSlider()
    {
        $datosPost = file_get_contents("php://input");
        $datosPost = json_decode($datosPost);
        if ($datosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $datosPost->length;
        }
        $buscar = $datosPost->search->value;
        $consulta = " and (nombre_slider LIKE '%$buscar%') ";
        $query = "SELECT *,
        concat('".RUTA_ARCHIVO."/archivo/".DOMINIO_ARCHIVO."/imagen_slider/',pathescritorio_slider) as rutacritorio_slider,
        concat('".RUTA_ARCHIVO."/archivo/".DOMINIO_ARCHIVO."/imagen_slider/',pathmobile_slider) as rutamobile_slider,
        pathescritorio_slider,
        pathmobile_slider
        FROM slider
        left join categoria using (id_categoria)
        WHERE  vigente_slider=$datosPost->vigente_slider
        $consulta
        order by fechacreacion_slider desc";
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
