<?php

use Firebase\JWT\JWT;

class JwtAuth
{

    public $key;
    public function __construct()
    {
        // [SEGURIDAD C2/C5] La clave de firma JWT se lee de config/Parametros.php
        // (archivo NO versionado). Fail-closed: si no está definida, se aborta en
        // vez de usar una clave conocida.
        if (!defined('JWT_KEY')) {
            http_response_code(500);
            die(json_encode(['status' => 'error', 'message' => 'Configuración de seguridad ausente.']));
        }
        $this->key = JWT_KEY;
    }

    public function signup($email, $password, $getToken = null)
    {
        // [SEGURIDAD A1] Antes el password (sha256) se comparaba dentro del WHERE.
        // Ahora se busca por CORREO o DNI y se verifica en PHP, soportando bcrypt y el
        // sha256 legado, con rehash transparente a bcrypt en cada login exitoso.
        // ($email es el identificador: puede ser correo o DNI.)
        $user = Usuario::join('staff', 'staff.id_staff', 'usuario.id_staff')
            ->where(function ($q) use ($email) {
                $q->where('staff.e_mail_staff', $email)
                    ->orWhere('staff.dni_staff', $email);
            })
            ->where("usuario.vigente_usuario", 1)
            ->first();
        if (!$user || !helpers::verifyPassword($password, $user->password_usuario)) {
            http_response_code(403);
            header("Login a Fallado");
            return;
        }
        if (helpers::passwordNeedsRehash($user->password_usuario)) {
            Usuario::where('id_usuario', $user->id_usuario)
                ->update(['password_usuario' => helpers::hashPassword($password)]);
        }
        if (!empty($user->session_id)) {
            return array(
                'status' => 'error',
                'message'  => "Se Inicio Session con la cuenta",
                'id_usuario' => $user->id_usuario,
            );
        }
        $signup = true;
        if ($signup) {
            $empresaVentaOnline=EmpresaVentaOnline::first();
            //Generar un toke y devolver
            $token = array(
                'id_empresa'=>(isset($empresaVentaOnline) ? $empresaVentaOnline->id_empresa_venta_online : null),
                'ruc_empresa_venta_online'=>(isset($empresaVentaOnline) ? $empresaVentaOnline->ruc_empresa_venta_online : null),
                'telefono_empresa_venta_online'=>(isset($empresaVentaOnline) ? $empresaVentaOnline->telefono_empresa_venta_online : null),
                'celular_empresa_venta_online'=>(isset($empresaVentaOnline) ? $empresaVentaOnline->celular_empresa_venta_online : null),
                'nombre_empresa_venta_online'=>(isset($empresaVentaOnline) ? $empresaVentaOnline->nombre_empresa_venta_online : null),
                // [LOGO] URL del logo horizontal para mostrarlo en la barra superior del panel.
                'urllogohorizontal_empresa_venta_online'=>(isset($empresaVentaOnline) ? $empresaVentaOnline->urllogohorizontal_empresa_venta_online : null),
                'sub' => $user->id_usuario,
                'email' => $user->e_mail_staff,
                'nombre' => $user->nombre_staff,
                'apellido_paterno' => $user->apellidopaterno_staff,
                'apellido_materno' => $user->apellidomaterno_staff,
                'tipo_usuario' => $user->rol_usuario,
                'imagen' => $user->pathfoto_usuario,
                'id_perfil' => $user->id_perfil,
                'id_bodega' => $user->id_bodega,
                //creacion del dato es el iat create_at
                'iat' => time(),
                //despues de una semana
                'expiracion' => time() + (1 * 24 * 60 * 60)
            );

            //el HS256 es para cifrar la llave
            $jwt = JWT::encode($token, $this->key, 'HS256');
            //decodificando el mismo token
            JWT::decode($jwt, $this->key, array('HS256'));
            if (is_null($getToken)) {
                return $jwt;
            } else {
                $new_sessid =JwtAuth::generarCodigo(12);
                //INICIO VERIFICACION DATOS EN SESION
                // Guardar Session usuario
                $user->session_id=$new_sessid;
                $user->save();
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
        // [SEGURIDAD C2] API-key estática del store público (movida a Parametros.php,
        // no versionado). Pendiente Lote 2: acotar esta key para que solo alcance
        // endpoints Api/* y no controllers de administración.
        if (defined('STORE_API_KEY') && $jwt === STORE_API_KEY) {
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
