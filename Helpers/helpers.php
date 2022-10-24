<?php

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
}
