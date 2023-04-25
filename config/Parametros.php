<?php
//PARA EL SERVIDOR
switch (true) {
    case (strpos($_SERVER['SERVER_NAME'], 'crm.sistemasdurand.com') !== false):
        $host = '';
        $username = '';
        $password = '';
        $base_datos = '';
        $ruta_archivo = '';
        define('RUTA_ARCHIVO', $ruta_archivo);
        break;
    default:
        $dominio = "";
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $base_datos = 'carrito_compras';
        $ruta_archivo = 'http://localhost/MVC_CRM/';
        define('RUTA_ARCHIVO', $ruta_archivo);
        break;
}
//
//CORREO ELECTRONICO
define('Host', '');
define('Username', '');
define('Password', '');
define('Port', '');
define('Email', '');
//Cloudinary
define('cloud_name', 'do7dzakiw');
define('api_key', '588565748574254');
define('api_secret', 'VkYxLX75qH0TYoIWpJ7jc4ePKQ0');
//-----------------
