<?php
//PARA EL SERVIDOR
switch (true) {
    case (strpos($_SERVER['SERVER_NAME'], 'crm.sistemasdurand.com') !== false):
        $host = '162.241.60.172';
        $username = 'siste268';
        $password = 'zSj55IiL2+e8:E';
        $base_datos = 'siste268_crmventas';
        $ruta_archivo = 'https://crm.sistemasdurand.com/';
        define('RUTA_ARCHIVO', $ruta_archivo);
        define('API_SUNAT', 'https://apigreenter.sistemasdurand.com');
        break;
    default:
        $dominio = "";
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $base_datos = 'carrito_compras';
        $ruta_archivo = 'http://localhost/MVC_CRM/';
        define('RUTA_ARCHIVO', $ruta_archivo);
        define('API_SUNAT', 'http://127.0.0.1:8000');
        break;
}
//
//CORREO ELECTRONICO
define('Host', 'smtp-relay.sendinblue.com');
define('Username', 'smithxd108@gmail.com');
define('Password', 'nPmB32Gcyw45Dbfg');
define('Port', '587');
//Cloudinary
define('cloud_name', 'do7dzakiw');
define('api_key', '588565748574254');
define('api_secret', 'VkYxLX75qH0TYoIWpJ7jc4ePKQ0');
//-----------------
