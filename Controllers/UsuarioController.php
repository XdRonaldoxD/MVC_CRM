<?php

use Illuminate\Support\Facades\Request;


require_once "Helpers/helpers.php";
require_once "models/Usuario.php";
require_once "models/Staff.php";
require_once "models/Categorias.php";
require_once "models/EmpresaVentaOnline.php";


class UsuarioController
{

    public function login()
    {
        $jwtAuth = new JwtAuth();
        // [LOGIN] El identificador llega como 'dni' (el login es por DNI). Se mantiene
        // respaldo a 'email' por compatibilidad. El backend acepta DNI o correo.
        $identificador = helpers::validar_input($_POST['dni'] ?? $_POST['email'] ?? '');
        $password = helpers::validar_input($_POST['password']);
        if (trim($identificador) === '') {
            http_response_code(404);
            echo json_encode("Ingrese su correo o DNI");
            return;
        }
        // [SEGURIDAD A1] Se pasa la contraseña (ya pasada por validar_input, para
        // mantener compatibilidad con los hashes existentes); signup la verifica.
        if (isset($_POST['getToken'])) {
            $signup = $jwtAuth->signup($identificador, $password, $_POST['getToken']);
        } else {
            $signup = $jwtAuth->signup($identificador, $password);
        }
        echo json_encode($signup);
    }

    public function RegistrarUsuario()
    {
        //Cifrar las contraseña - Cifrando 4 veces
        $pwd = helpers::hashPassword($_POST['password_usuario']); // [SEGURIDAD A1] bcrypt
        $staff = [
            'nombre_staff' => $_POST['nombre_usuario'],
            "apellidopaterno_staff" => $_POST['apellido_p_usuario'],
            "apellidomaterno_staff" => $_POST['apellido_m_usuario'],
            'e_mail_staff' => $_POST['email_usuario']
        ];
        $staff = Staff::create($staff);
        $usuario = [
            "password_usuario" => $pwd,
            "id_staff" => $staff->id_staff,
            'vigente_usuario' => 1
        ];
        Usuario::create($usuario);
        $data = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'El usuario creado',
            'dato_user' => $staff->nombre_staff
        );
        echo json_encode($data);
    }
    public function EliminarSesion()
    {
        $usuario = Usuario::where("id_usuario", $_POST['id_usuario'])->first();
        $usuario->session_id = null;
        $usuario->save();
        echo json_encode('Sessión destruida con exito');
    }

    public function ConsultaUsuario()
    {
        $usuario = Usuario::where('id_usuario', $_POST['user_id'])
            ->where("session_id", str_replace('"', '', $_POST['session_id']))
            ->first();
        $repuesta = false;
        if (empty($usuario)) {
            $repuesta = true;
        }
        echo json_encode($repuesta);
    }

    public function VerificarToken(Request $request)
    {
        $tokne = $request->header('Authorization');
        $jwtAth = new JwtAuth();
        $checktoken = $jwtAth->checktoken($tokne);
        if ($checktoken) {
            echo "login correcto";
        } else {
            echo "login incorrecto";
        }
    }

    public function GenerarUrlAmigable()
    {
        $categoria = Categorias::get();
        foreach ($categoria as $key => $element) {
            $urlAmigable = "";
            if ($element->glosa_categoria != "") {
                $urlAmigable .= str_replace(" ", "-", $element->glosa_categoria);
            }
            $urlAmigable = str_replace("/", "-", $urlAmigable);
            $urlAmigable = str_replace("\\", "-", $urlAmigable);
            $urlAmigable = str_replace("+", "-", $urlAmigable);

            $element->urlamigable_categoria = $urlAmigable;
            $element->save();
        }

        echo "Generado Correctamente";
    }

    public function ConsultarDominio()
    {
        $empresaVentaOnline = EmpresaVentaOnline::where('dominio_empresa_venta_online', $_SERVER['SERVER_NAME'])->first();
        if (isset($empresaVentaOnline)) {
            $datos=[
                'icono'=>$empresaVentaOnline->urlicono_empresa_venta_online,
                "nombre_empresa"=>empty($empresaVentaOnline->nombre_empresa_venta_online) ? null : $empresaVentaOnline->nombre_empresa_venta_online
            ];
            echo json_encode($datos);
        } else {
            die(http_response_code(404));
        }
    }

}