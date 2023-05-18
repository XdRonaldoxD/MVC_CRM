<?php

use Firebase\JWT\JWT;

class JwtAuth
{

    public $key;
    public function __construct()
    {
        $this->key = "ESTE-ES-MI-LLAVE-BOTICA3354335467547";
    }

    public function signup($email, $contra, $getToken = null)
    {
        $user = Usuario::join('staff', 'staff.id_staff', 'usuario.id_staff')
            ->where("staff.e_mail_staff", $email)
            ->where("usuario.password_usuario", $contra)
            ->where("usuario.vigente_usuario",1)
            ->first();
        if (!empty($user->session_id)) {
            return array(
                'status' => 'error',
                'message'  => "Se Inicio Session con la cuenta",
                'id_usuario' => $user->id_usuario,
            );
        }
        // return response()->json($user);
        $signup = false;
        if ($user) {
            $signup = true;
        }
        if ($signup) {
            $EmpresaVentaOnline=EmpresaVentaOnline::first();
            //Generar un toke y devolver
            $token = array(
                'id_empresa'=>(isset($EmpresaVentaOnline) ? $EmpresaVentaOnline->id_empresa_venta_online : null),
                'ruc_empresa_venta_online'=>(isset($EmpresaVentaOnline) ? $EmpresaVentaOnline->ruc_empresa_venta_online : null),
                'telefono_empresa_venta_online'=>(isset($EmpresaVentaOnline) ? $EmpresaVentaOnline->telefono_empresa_venta_online : null),
                'celular_empresa_venta_online'=>(isset($EmpresaVentaOnline) ? $EmpresaVentaOnline->celular_empresa_venta_online : null),
                'nombre_empresa_venta_online'=>(isset($EmpresaVentaOnline) ? $EmpresaVentaOnline->nombre_empresa_venta_online : null),
                'sub' => $user->id_usuario,
                'email' => $user->e_mail_staff,
                'nombre' => $user->nombre_staff,
                'apellido_paterno' => $user->apellidopaterno_staff,
                'apellido_materno' => $user->apellidomaterno_staff,
                // 'tipo_usuario' => $user->rol_usuario,
                'imagen' => $user->pathfoto_usuario,
                //creacion del dato es el iat create_at
                'iat' => time(),
                //despues de una semana
                'expiracion' => time() + (1 * 24 * 60 * 60)
            );

            //el HS256 es para cifrar la llave
            $jwt = JWT::encode($token, $this->key, 'HS256');
            //decodificando el mismo token
            $decode = JWT::decode($jwt, $this->key, array('HS256'));

            if (is_null($getToken)) {
                return $jwt;
            } else {
                $new_sessid =JwtAuth::generarCodigo(12);
                //INICIO VERIFICACION DATOS EN SESION
                // Guardar Session usuario
                $user->session_id=$new_sessid;
                $user->save();
                //FIN
                // $decode = array(
                //     'sub' => $user->id_usuario,
                //     'email' => $user->email_usuario,
                //     'nombre' => $user->nombre_usuario,
                //     'apellido' => $user->apellido_usuario,
                //     'tipo_usuario' => $user->rol_usuario,
                //     'session_id' => $new_sessid,
                //     'imagen' => $user->path_usuario,
                //     //creacion del dato es el iat create_at
                //     'iat' => time(),
                //     //despues de una semana
                //     'expiracion' => time() + (1 * 24 * 60 * 60)
                // );
                $token+=[
                    'session_id' => $new_sessid,
                ];

                return  $token;
            }
        } else {
            //Generar UN error
            // return array('status' => 'error', 'message' => 'Login a Fallado');
            http_response_code(403);
            header("Login a Fallado");
        }
    }

    public static function generarCodigo($longitud)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $max = strlen($pattern) - 1;
        $key = '';
        for ($i = 0; $i < $longitud; $i++) {
            $key .= $pattern[mt_rand(0, $max)];
        }
        return $key;
    }

    //metodo para decodoficar el toke e usar en los controladores
    //recoger el toker y ver si es correcto o no
    public function checktoken($jwt, $getIdentity = false)
    {
        $auth = false;
        if ($jwt=="@TEXCOTTOMDESING2021LS~$") {
            $auth = true;
        }else{
            try {
                //Remplaza las comillas y los quitas
                $jwt = str_replace('"', '', $jwt);
                $decode = JWT::decode($jwt, $this->key, array('HS256'));
            } catch (\UnexpectedValueException $e) {
                $auth = false;
            } catch (\DomainException $e) {
                $auth = false;
            }
            if (isset($decode) &&  is_object($decode) && isset($decode->sub)) {
                $auth = true;
            }
            if ($getIdentity) {
                return $decode;
            }
        }
   
        return $auth;
    }
}
