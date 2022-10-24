<?php

//EVITAR ATAQUE DDOS(NOTA CREAR LA CARPETA flood el sitio que se creara la funciona en este 
// caso sera config del proyecto)
define("SCRIPT_ROOT", dirname(__FILE__));
// número de solicitudes de página permitidas para el usuario
define("CONTROL_MAX_REQUESTS", 100);
// intervalo de tiempo para comenzar a contar las solicitudes de página (segundos)
define("CONTROL_REQ_TIMEOUT", 5);
// Segundos para castigar a la usuario que ha excedido en hacer solicitudes.
define("CONTROL_BAN_TIME", 10);
// directorio de escritura para mantener los datos del script
define("SCRIPT_TMP_DIR", SCRIPT_ROOT."/flood");
// no necesitas editar debajo de esta línea
define("USER_IP", $_SERVER["REMOTE_ADDR"]);
define("CONTROL_DB", SCRIPT_TMP_DIR."/ctrl");
define("CONTROL_LOCK_DIR", SCRIPT_TMP_DIR."/lock");
define("CONTROL_LOCK_FILE", CONTROL_LOCK_DIR."/".md5(USER_IP));
@mkdir(CONTROL_LOCK_DIR);
@mkdir(SCRIPT_TMP_DIR);
if (file_exists(CONTROL_LOCK_FILE)) {
    if (time()-filemtime(CONTROL_LOCK_FILE) > CONTROL_BAN_TIME) {
        // this user has complete his punishment
        unlink(CONTROL_LOCK_FILE);
    } else {
        // too many requests
        echo "<h1>DENIED</h1>";
        echo "Please try later.";
        touch(CONTROL_LOCK_FILE);
        die;
    }
}
class EvitarDDos{
     static function antiflood_countaccess() {
        // conteo de solicitudes y última hora de acceso
        $control = Array();
        if (file_exists(CONTROL_DB)) {
            $fh = fopen(CONTROL_DB, "r");
            $control = array_merge($control, unserialize(fread($fh, filesize(CONTROL_DB))));
            fclose($fh);
        }
        if (isset($control[USER_IP])) {
            if (time()-$control[USER_IP]["t"] < CONTROL_REQ_TIMEOUT) {
                $control[USER_IP]["c"]++;
            } else {
                $control[USER_IP]["c"] = 1;
            }
        } else {
            $control[USER_IP]["c"] = 1;
        }
        $control[USER_IP]["t"] = time();
    
        if ($control[USER_IP]["c"] >= CONTROL_MAX_REQUESTS) {
            // this user did too many requests within a very short period of time
            $fh = fopen(CONTROL_LOCK_FILE, "w");
            fwrite($fh, USER_IP);
            fclose($fh);
        }
    
        // writing updated control table
        $fh = fopen(CONTROL_DB, "w");
        fwrite($fh, serialize($control));
        fclose($fh);
    }
}

