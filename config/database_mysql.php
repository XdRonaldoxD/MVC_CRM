<?php


define('DB_HOST', $host);
define('DB_USER', $username);
define('DB_PASS', $password);
define('DB_NAME', $base_datos);

class database
{
    public static function conectar()
    {
        $conexion = new  PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS,
        array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "
        SET NAMES 'utf8';
        SET sql_mode = '';
        ",
            // [FIX 2014] Bufferiza los resultados en el cliente para que una consulta
            // no bloquee a la siguiente cuando la anterior no se consumió por completo.
            // Necesario en el servidor (PDO no-buffered por defecto); en local no se notaba.
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        ));
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $conexion;
    }
}
