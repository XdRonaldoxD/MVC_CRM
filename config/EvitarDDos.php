<?php
class EvitarDDos
{
    public function limitarSolicitudes($limite, $tiempo)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $archivo = "archivo/registrosDDos/"  . md5($_SERVER['REQUEST_URI']) . '.txt';
        $tiempo_actual = time();
        $registros = array();
        if (!is_dir('archivo/registrosDDos')) {
            mkdir('archivo/registrosDDos');
            chmod('archivo/registrosDDos', 0777);
        }
        if (file_exists($archivo)) {
            $registros = unserialize(file_get_contents($archivo));
        }
        $registros = array_filter($registros, function ($registro) use ($tiempo_actual, $tiempo) {
            return ($registro['timestamp'] + $tiempo) > $tiempo_actual;
        });
        $registros[] = array('timestamp' => $tiempo_actual);
        file_put_contents($archivo, serialize($registros));
        return (count($registros) > $limite);
    }
}
