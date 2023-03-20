<?php


use Verot\Upload\Upload;

require_once "models/Slider.php";
require_once "models/ConsultaGlobal.php";


class SliderController
{

    public function ActualizarCrearSlider()
    {
        try {
            $Formulario = json_decode($_POST['formulario']);
            $slider = [
                'nombre_slider' => $Formulario->titulo_slider,
                'id_categoria' => $Formulario->id_categoria

            ];
            if (isset($_FILES['imagen_escritorio']) && !empty($_FILES['imagen_escritorio'])) {
                $imagen = $_FILES['imagen_escritorio']['name'];
                $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
                $nombre_imagen = preg_replace('([^A-Za-z0-9])', '', $nombre_imagen);
                // $temp = $_FILES['imagen']['tmp_name'];
                //crear el directorio
                if (!file_exists(__DIR__ . "/../archivo/imagen_slider")) {
                    mkdir(__DIR__ . "/../archivo/imagen_slider", 0777, true);
                }
                $Slider = Slider::where('id_slider', $Formulario->id_slider)->first();
                if (isset($Slider) && $Slider->pathescritorio_slider) {
                    if (file_exists(__DIR__ . "/../archivo/imagen_slider/$Slider->pathescritorio_slider")) {
                        unlink(__DIR__ . "/../archivo/imagen_slider/$Slider->pathescritorio_slider");
                    }
                }
                // GUARDA LA IMAGEN
                $fechacreacion = date('Y-m-d H:i:s');
                $separaFecha = explode(" ", $fechacreacion);
                $Fecha = explode("-", $separaFecha[0]);
                $path =  $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . $nombre_imagen;
                // move_uploaded_file($temp, __DIR__ . "/../archivo/imagen_categoria/$path.'.'.$ext"); //

                //SEGUNDA LIBRERIA
                $foo = new Upload($_FILES['imagen_escritorio']);
                if (!$foo) {
                    echo json_encode("Error");
                    die;
                }
                $foo->file_new_name_body = $path;
                $foo->image_resize          = true;
                //SI DESEAMOS PONER EL MISMO TAMAÑO Y  DARLE LA MISMA ANCHO Y ALTO COMENTAR EL IMAGE RATIO
                // $foo->image_ratio           = true;
                //
                $foo->image_x               = 1110;
                $foo->image_y               = 440;
                $foo->process(__DIR__ . "/../archivo/imagen_slider/");
                if ($foo->processed) {
                    $foo->clean();
                } else {
                    echo 'error : ' . $foo->error;
                    die();
                }
                $pathescritorio_slider =  $path . '.' . $ext;
                $slider += [
                    'pathescritorio_slider' => $pathescritorio_slider,
                ];
            }
            if (isset($_FILES['imagen_mobile']) && !empty($_FILES['imagen_mobile'])) {
                $imagen = $_FILES['imagen_mobile']['name'];
                $ext = pathinfo($imagen, PATHINFO_EXTENSION);
                $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
                $nombre_imagen = preg_replace('([^A-Za-z0-9])', '', $nombre_imagen);
                // $temp = $_FILES['imagen']['tmp_name'];
                //crear el directorio
                if (!file_exists(__DIR__ . "/../archivo/imagen_slider")) {
                    mkdir(__DIR__ . "/../archivo/imagen_slider", 0777, true);
                }
                $Slider = Slider::where('id_slider', $Formulario->id_slider)->first();
                if (isset($Slider) && $Slider->pathescritorio_slider) {
                    if (file_exists(__DIR__ . "/../archivo/imagen_slider/$Slider->pathescritorio_slider")) {
                        unlink(__DIR__ . "/../archivo/imagen_slider/$Slider->pathescritorio_slider");
                    }
                }


                // GUARDA LA IMAGEN
                $fechacreacion = date('Y-m-d H:i:s');
                $separaFecha = explode(" ", $fechacreacion);
                $Fecha = explode("-", $separaFecha[0]);
                $path =  $Fecha[0] . $Fecha[1] . $Fecha[2] . time() . $nombre_imagen;
                // move_uploaded_file($temp, __DIR__ . "/../archivo/imagen_categoria/$path.'.'.$ext"); //

                //SEGUNDA LIBRERIA
                $foo = new Upload($_FILES['imagen_mobile']);
                if (!$foo) {
                    echo json_encode("Error");
                    die;
                }
                $foo->file_new_name_body = $path;
                $foo->image_resize          = true;
                //SI DESEAMOS PONER EL MISMO TAMAÑO Y  DARLE LA MISMA ANCHO Y ALTO COMENTAR EL IMAGE RATIO
                // $foo->image_ratio           = true;
                //
                $foo->image_x               = 510;
                $foo->image_y               = 395;
                $foo->process(__DIR__ . "/../archivo/imagen_slider/");
                if ($foo->processed) {
                    $foo->clean();
                } else {
                    echo 'error : ' . $foo->error;
                    die();
                }
                $pathmobile_slider =  $path . '.' . $ext;
                $slider += [
                    'pathmobile_slider' => $pathmobile_slider,
                ];
            }

            if ($Formulario->accion === "ACTUALIZAR") {
                Slider::where('id_slider', $Formulario->id_slider)->update($slider);
                $respuesta = "Actualizado";
            } else {
                $slider += [
                    'fechacreacion_slider' => date('Y-m-d H:i:s'),
                    'vigente_slider' => 1,
                ];
                Slider::create($slider);
                $respuesta = "Creado";
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die;
        }
        echo json_encode("$respuesta");
    }

    public function GestionActivoDesactivado()
    {
        if ($_POST['accion'] === 'ACTIVAR') {
            $data = [
                'vigente_slider' => 1
            ];
        } else {
            $data = [
                'vigente_slider' => 0
            ];
        }
        Slider::where("id_slider", $_POST['id_slider'])->update($data);
        echo json_encode("exitoso");
    }



    public function ListarSlider()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;
        $consulta = " and (nombre_slider LIKE '%$buscar%') ";
        $query = "SELECT * FROM slider  
        left join categoria using (id_categoria)
        WHERE  vigente_slider=$DatosPost->vigente_slider
         $consulta 
        order by fechacreacion_slider desc";
        $ConsultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $DatosPost->start ";
        $ConsultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => count($ConsultaGlobalLimit),
            "recordsFiltered" => count($ConsultaGlobalLimit),
            "data" => $ConsultaGlobal
        );
        echo json_encode($datos);
    }
}
