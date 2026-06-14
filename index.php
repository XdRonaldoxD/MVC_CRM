<?php

date_default_timezone_set('America/Lima');
// [QA-FIX] La API debe responder SIEMPRE JSON limpio. Con display_errors activo
// (típico en local) cualquier Notice/Warning se imprime antes del json_encode y
// corrompe la respuesta (el front falla al parsear). Se siguen registrando en el log
// del servidor, pero no se emiten al cuerpo de la respuesta.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
// Definir constante con directorio actual
define('PROY_RUTA', __DIR__);
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS");
header("Allow: GET, POST, PUT, DELETE,OPTIONS");
// [PERF] Cachea el preflight CORS 24h: el navegador deja de re-enviar el OPTIONS
// para la misma petición durante ese lapso.
header("Access-Control-Max-Age: 86400");
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
// [SEGURIDAD] Headers de seguridad básicos (clickjacking, MIME sniffing, fuga de referer).
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');

//EVITAR ATAQUE DDOS(PARA LA SOBRECARGA DE PETICIONES)
require_once "config/EvitarDDos.php";
//50 solicitudes x 60 Segundos que es 1 minuto
if ((new EvitarDDos())->limitarSolicitudes(50, 60)) {
    header('HTTP/1.1 429 Too Many Requests');
    die('Too Many Requests');
}

//-----------------------------------------------------
require_once "vendor/autoload.php";
require_once "config/Parametros.php";

// [SEGURIDAD A2] CORS por allowlist en lugar de '*'. Se refleja el Origin solo si
// está en la lista (Parametros.php) o es subdominio de los dominios corporativos.
// Así ninguna web de terceros puede invocar la API desde el navegador de la víctima.
$cors_permitidos = defined('CORS_ORIGINS_PERMITIDOS') ? json_decode(CORS_ORIGINS_PERMITIDOS, true) : [];
$cors_dominios_base = ['sistemaboticarosa.com', 'sistemasdurand.com'];
$cors_origen = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$cors_ok = false;
if ($cors_origen) {
    if (is_array($cors_permitidos) && in_array($cors_origen, $cors_permitidos, true)) {
        $cors_ok = true;
    } else {
        $cors_host = parse_url($cors_origen, PHP_URL_HOST);
        if (is_string($cors_host)) {
            foreach ($cors_dominios_base as $cors_base) {
                if ($cors_host === $cors_base || substr($cors_host, -strlen('.' . $cors_base)) === '.' . $cors_base) {
                    $cors_ok = true;
                    break;
                }
            }
        }
    }
}
if ($cors_ok) {
    header('Access-Control-Allow-Origin: ' . $cors_origen);
    header('Vary: Origin');
}

// [PERF] El preflight (OPTIONS) se responde de inmediato con los headers CORS ya
// puestos, sin ejecutar controladores ni tocar la BD.
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
require_once "config/database.php";
require_once "config/database_mysql.php";
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
    $Authorization = null;
    $headers = apache_request_headers();
    if (isset($_GET['Authorization'])) {
        $Authorization = $_GET['Authorization'];
    }
    if (isset($headers['Authorization'])) {
        $Authorization = $headers['Authorization'];
    }
    if (isset($headers['authorization'])) {
        $Authorization = $headers['authorization'];
    }
    if ($Authorization) {
        $jwtAth = new JwtAuth();
        $checktoken = $jwtAth->checktoken($Authorization);
        // [SEGURIDAD C1] Se elimina el override "$checktoken = true;" que anulaba
        // por completo la validación del token. Ahora la autenticación es real:
        // solo pasan peticiones con JWT válido (admin) o la API-key del store.

        // [SEGURIDAD C2] Acotar la API-key estática del store: solo puede alcanzar
        // endpoints públicos (cualquier Apicontroller=*) más un allowlist mínimo de
        // controllers admin que la tienda usa legítimamente (checkout). Así, si la
        // key se filtra, NO da acceso a Usuario/Caja/Producto-admin, etc.
        $esStoreKey = (defined('STORE_API_KEY') && str_replace('"', '', $Authorization) === STORE_API_KEY);
        if ($checktoken && $esStoreKey) {
            $store_controllers_permitidos = ['NotaVenta'];
            $controller_admin = isset($_GET['controller']) ? $_GET['controller'] : null;
            if ($controller_admin !== null && !in_array($controller_admin, $store_controllers_permitidos, true)) {
                $checktoken = false; // store key intentando un controller admin no permitido
            }
        }

        if (!$checktoken) {
            http_response_code(401);
            $data = array(
                'status' => 'error',
                'code' => 401,
                'message' => 'El usuario no esta Autenticado',
            );
            echo json_encode($data);
        } else {
            // [PERMISOS] Autorización por perfil para controllers de administración.
            // No aplica al store (STORE_API_KEY) ni a los Apicontroller públicos.
            // El id_perfil se toma del JWT (confiable). El ADMINISTRADOR ve todo.
            if (isset($_GET['controller']) && !$esStoreKey) {
                require_once "Helpers/PermisoGate.php";
                $identity_perm = $jwtAth->checktoken($Authorization, true);
                $id_perfil_perm = (is_object($identity_perm) && isset($identity_perm->id_perfil)) ? (int) $identity_perm->id_perfil : 0;
                $accion_perm = isset($_GET['action']) ? $_GET['action'] : '';
                if (!PermisoGate::permitido($id_perfil_perm, $_GET['controller'], $accion_perm)) {
                    http_response_code(403);
                    echo json_encode(array(
                        'status' => 'error',
                        'code' => 403,
                        'message' => 'No tiene permiso para acceder a este módulo.',
                    ));
                    die();
                }
            }
            if (isset($_GET['controller']) || $_GET['Apicontroller']) {
                if (isset($_GET['Apicontroller'])) {
                    $controller = $_GET['Apicontroller'];
                } else {
                    $controller = $_GET['controller'];
                }
                $nombre_controlador = $controller . "Controller";
            } else {
                echo "No exite la Pagina";
                die(http_response_code(403));
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
        if (isset($_GET['controller']) && ($_GET['controller'] == 'Usuario' || $_GET['controller'] == 'AnularDocumento' || $_GET['controller'] == 'Script') ) {
            $nombre_controlador = $_GET['controller'] . "Controller";
        } else {
            echo "No exite la Pagina";
            die(http_response_code(404));
        }
        if (isset($_GET['action']) && class_exists($nombre_controlador)) {
            $controlador = new $nombre_controlador();
            if (isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                $accion = $_GET['action'];
                $controller = new $nombre_controlador();
                $controller->$accion();
            } else {
                echo "No existe la pagina";
                die(http_response_code(404));
            }
        } else {
            echo "No existe la pagina Inicio";
            die(http_response_code(404));
        }
    }
}
