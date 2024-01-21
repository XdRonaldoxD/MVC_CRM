<?php

require_once "models/EmpresaVentaOnline.php";
class EmpresaController
{
    protected $dominio_empresa;
    public function __construct()
    {
        if (!isset($_GET['dominio']) && !empty($_GET['dominio'])) {
            echo "No exite el dominio";
            die(http_response_code(404));
        }
        $this->dominio_empresa = $_GET['dominio'];
    }
    public function traerDatosEmpresa()
    {
        $datos = [
            'dominio_empresa_venta_online' => $this->dominio_empresa
        ];
        $datos = $this->obtenerDatos($datos);
        echo json_encode($datos);
    }
    private static function obtenerDatos($datos)
    {
        $data_empresa = EmpresaVentaOnline::where('dominio_empresa_venta_online',$datos['dominio_empresa_venta_online'])->first()->toArray();
        $scripts=[];
        if ($data_empresa['pixelgoogle_empresa_venta_online']) {
            $patron = '/<script.*?>(.*?)<\/script>/is';
            preg_match_all($patron, $data_empresa['pixelgoogle_empresa_venta_online'], $coincidencias);

            // Si se encontraron coincidencias, agrégalas al arreglo de scripts
            if (!empty($coincidencias[1])) {
                $scripts = $coincidencias[1];
            }
        }
        $scriptsfacebook = [];
        $noscriptsfacebook = [];
        if ($data_empresa['pixelfacebook_empresa_venta_online']) {
            // Utiliza una expresión regular para buscar y separar los fragmentos de script y noscript
            $patron = '/<(script|noscript)>(.*?)<\/\1>/is';
            preg_match_all($patron, $data_empresa['pixelfacebook_empresa_venta_online'], $coincidencias, PREG_SET_ORDER);
            foreach ($coincidencias as $coincidencia) {
                $etiqueta = $coincidencia[1]; // Indica si es una etiqueta script o noscript
                $contenido = $coincidencia[2]; // Extrae el contenido de la etiqueta
                if ($etiqueta === 'script') {
                    $scriptsfacebook[] = $contenido;
                } elseif ($etiqueta === 'noscript') {
                    $noscriptsfacebook[] = $contenido;
                }
            }
        }
        $facebook=[
            "script"=>$scriptsfacebook,
            "noscript"=>$noscriptsfacebook
        ];
        $data_empresa+= [
            "pixelgoogle" => $scripts,
            "pixelfacebook" => $facebook
        ];
        return $data_empresa;
    }
}
