<?php

date_default_timezone_set('America/Lima');
// Definir constante con directorio actual
define('PROY_RUTA', __DIR__);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS");
header("Allow: GET, POST, PUT, DELETE,OPTIONS");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');


//EVITAR ATAQUE DDOS(PARA LA SOBRECARGA DE PETICIONES)
// require_once "config/EvitarDDos.php";
// EvitarDDos::antiflood_countaccess();
//
require_once "vendor/autoload.php";
require_once "config/database.php";
require_once "Helpers/helpers.php";
require_once "Helpers/JwtAuth.php";

require 'phpMailer/Exception.php';
require 'phpMailer/PHPMailer.php';
require 'phpMailer/SMTP.php';

if (isset($_GET['controller'])) {
    $classname = $_GET['controller'] . "Controller";
    include "Controllers/" . $classname . '.php';
}
//API
if (isset($_GET['Apicontroller'])) {
    $classname = $_GET['Apicontroller'] . "Controller";
    include "Controllers/Api/" . $classname . '.php';
}
//
//REQUES VEO SI ESTAN LAS PETICIONES ENVIANDO EL CONTROLADOR Y SU ACCION SI NO ENVIA NO ENTRARA 
if ($_SERVER['REQUEST_METHOD'] === "POST" || $_SERVER['REQUEST_METHOD'] === "GET") {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $jwtAth = new JwtAuth();
        $checktoken = $jwtAth->checktoken($headers['Authorization']);
        // $checktoken=true;
        if (!$checktoken) {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'El usuario no esta Autenticado',
            );
            echo json_encode($data);
        } else {
            if (isset($_GET['controller']) || $_GET['Apicontroller']) {
                if (isset($_GET['Apicontroller'])) {
                    $controller = $_GET['Apicontroller'];
                } else {
                    $controller = $_GET['controller'];
                }
                $nombre_controlador = $controller . "Controller";
            } else {
                echo "No exite la Pagina";
                die();
            }
            if (isset($_GET['action']) && class_exists($nombre_controlador)) {
                $controlador = new $nombre_controlador();
                if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                    $accion = $_GET['action'];
                    $controller = new $nombre_controlador();
                    $controller->$accion();
                } else {
                    echo "No existe la pagina";
                }
            } else {
                http_response_code(403);
            }
        }
        die();
    } else {
        if (isset($_GET['controller']) && $_GET['controller']=='Usuario') {
            $nombre_controlador = $_GET['controller'] . "Controller";
        } else {
            http_response_code(403);
            die();
        }
        if (isset($_GET['action']) && class_exists($nombre_controlador)) {
            $controlador = new $nombre_controlador();
            if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                $accion = $_GET['action'];
                $controller = new $nombre_controlador();
                $controller->$accion();
            } else {
                // echo "No existe la pagina";
                http_response_code(403);
            }
        } else {
            // echo "No existe la pagina Inicio";
            http_response_code(403);
        }
    }
    die();
}

