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
            // [FIX 2014] Bufferiza los resultados en el cliente para que una consulta
            // no bloquee a la siguiente cuando la anterior no se consumió por completo.
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        ));
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        // [FIX 2014] Antes estos dos SET iban juntos en MYSQL_ATTR_INIT_COMMAND
        // como multi-statement (separados por ';'), lo que dejaba un result set
        // sin consumir y reventaba la PRIMERA consulta de cada conexión. Ejecutarlos
        // por separado con exec() consume el resultado y evita el error.
        $conexion->exec("SET NAMES 'utf8'");
        $conexion->exec("SET sql_mode = ''");
        return $conexion;
    }
}
