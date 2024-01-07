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
