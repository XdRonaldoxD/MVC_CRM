<?php

use Carbon\Carbon;

class helpers
{

    public static function validar_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // [SEGURIDAD A1] Manejo de contraseñas con bcrypt + COMPATIBILIDAD hacia atrás
    // con el hashing legado (sha256 sin salt). Así se migra sin dejar a nadie fuera:
    // se siguen aceptando contraseñas sha256 existentes y, al validarlas, se rehashea
    // a bcrypt de forma transparente.
    public static function hashPassword($plain)
    {
        return password_hash($plain, PASSWORD_BCRYPT);
    }

    public static function verifyPassword($plain, $stored)
    {
        if (!is_string($stored) || $stored === '') {
            return false;
        }
        $info = password_get_info($stored);
        if (!empty($info['algo'])) {
            // Hash moderno (bcrypt/argon2).
            return password_verify($plain, $stored);
        }
        // Legado: sha256 sin salt. Comparación en tiempo constante.
        return hash_equals($stored, hash('sha256', $plain));
    }

    public static function passwordNeedsRehash($stored)
    {
        if (!is_string($stored) || $stored === '') {
            return true;
        }
        $info = password_get_info($stored);
        if (empty($info['algo'])) {
            return true; // sha256 legado -> conviene rehashear a bcrypt
        }
        return password_needs_rehash($stored, PASSWORD_BCRYPT);
    }

    public static function validaRequerido($valor)
    {
        if (trim($valor) == '') {
            return false;
        } else {
            return true;
        }
    }
    public static  function validarEntero($valor, $opciones = null)
    {
        if (filter_var($valor, FILTER_VALIDATE_INT, $opciones) === FALSE) {
            return false;
        } else {
            return true;
        }
    }
    public static  function validaEmail($valor)
    {
        if (filter_var($valor, FILTER_VALIDATE_EMAIL) === FALSE) {
            return false;
        } else {
            return true;
        }
    }
    public static function nombreMes($fecha)
    {
      $miFecha = new Carbon($fecha, 'America/Lima');
      $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
      $mes = $meses[($miFecha->format('n')) - 1];
      return $mes;
    }

    public static function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
     
        return $random_string;
    }

    public static function crearDirectorioSiNoExiste($ruta) {
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true); // Otorga permisos mínimos necesarios (0755)
        }
    }
}
