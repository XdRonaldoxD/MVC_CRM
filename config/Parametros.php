<?php
//PARA EL SERVIDOR
switch ($_SERVER['SERVER_NAME']) {
    case 'crm.sistemasdurand.com':
        $host = '162.241.60.432';
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
//