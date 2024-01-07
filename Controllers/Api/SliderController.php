<?php

require_once "models/ConsultaGlobal.php";
class SliderController{

    public function ListarSlider(){
        $query="SELECT
        UPPER(nombre_slider) as title,
        concat('".RUTA_ARCHIVO."/archivo/".DOMINIO_ARCHIVO."/imagen_slider/',pathescritorio_slider)  as image_classic,
        concat('".RUTA_ARCHIVO."/archivo/".DOMINIO_ARCHIVO."/imagen_slider/',pathescritorio_slider)  as image_full,
        concat('".RUTA_ARCHIVO."/archivo/".DOMINIO_ARCHIVO."/imagen_slider/',pathmobile_slider)  as image_mobile,
        texto_slider,urlamigable_categoria
        FROM slider
        inner join categoria using (id_categoria)
        where vigente_slider=1
        ";
        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        echo json_encode($consultaGlobalLimit);
    }
}