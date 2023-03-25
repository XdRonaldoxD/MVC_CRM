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
        $email = helpers::validar_input($_POST['email']);
        $password = helpers::validar_input($_POST['password']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(404);
            header("correo erroneo");
        } else {
            $pwd = hash('sha256', $password);
            if (isset($_POST['getToken'])) {
                $signup = $jwtAuth->signup($email, $pwd, $_POST['getToken']);
            } else {
                $signup = $jwtAuth->signup($email, $pwd);
            }
        }
        echo json_encode($signup);
    }

    public function RegistrarUsuario()
    { 
        //Cifrar las contraseña - Cifrando 4 veces
        $pwd = hash('sha256', $_POST['password_usuario']);
        $staff=[
            'nombre_staff'=>$_POST['nombre_usuario'],
            "apellidopaterno_staff"=>$_POST['apellido_p_usuario'],
            "apellidomaterno_staff"=>$_POST['apellido_m_usuario'],
            'e_mail_staff'=>$_POST['email_usuario']
        ];
        $staff=Staff::create($staff);
        $usuario=[
            "password_usuario"=>$pwd,
            "id_staff"=>$staff->id_staff,
            'vigente_usuario'=>1
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
        $usuario =Usuario::where("id_usuario",$_POST['id_usuario'])->first();
        $usuario->session_id=null;
        $usuario->save();
        echo json_encode('Sessión destruida con exito');
    }

    public function ConsultaUsuario()
    {
        $usuario = Usuario::where('id_usuario',$_POST['user_id'])
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
}
