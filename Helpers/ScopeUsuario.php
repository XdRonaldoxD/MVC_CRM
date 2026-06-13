<?php

require_once "models/ConsultaGlobal.php";
require_once "Helpers/JwtAuth.php";

/**
 * [SCOPE] Limita la visibilidad de bodega/sucursal por usuario.
 *  - ADMINISTRADOR (id_perfil=1): ve TODO (los métodos devuelven null = sin filtro).
 *  - Otros perfiles: solo su bodega/sucursal asignada (staff.id_bodega / staff.id_sucursal).
 * La identidad sale del JWT (confiable). Centraliza la lógica para reutilizarla en
 * cualquier controller sin duplicar consultas.
 */
class ScopeUsuario
{
    private static $identity = null;
    private static $identityResuelta = false;
    private static $staff = null;
    private static $staffResuelto = false;

    public static function identidad()
    {
        if (self::$identityResuelta) {
            return self::$identity;
        }
        self::$identityResuelta = true;
        $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
        $token = null;
        if (isset($_GET['Authorization'])) {
            $token = $_GET['Authorization'];
        } elseif (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) {
            $token = $headers['authorization'];
        }
        self::$identity = $token ? (new JwtAuth())->checktoken($token, true) : null;
        return self::$identity;
    }

    public static function esAdmin()
    {
        $i = self::identidad();
        return is_object($i) && isset($i->id_perfil) && (int) $i->id_perfil === 1;
    }

    private static function staffActual()
    {
        if (self::$staffResuelto) {
            return self::$staff;
        }
        self::$staffResuelto = true;
        $i = self::identidad();
        $idUsuario = (is_object($i) && isset($i->sub)) ? (int) $i->sub : 0;
        if ($idUsuario <= 0) {
            return self::$staff = null;
        }
        $rows = (new ConsultaGlobal())->ConsultaGlobal(
            "SELECT st.id_staff, st.id_sucursal, st.id_bodega
             FROM staff st INNER JOIN usuario u ON u.id_staff = st.id_staff
             WHERE u.id_usuario = " . $idUsuario
        );
        return self::$staff = (isset($rows[0]) ? $rows[0] : null);
    }

    /** IDs de bodega permitidas. null = todas (admin). [-1] = el usuario no tiene bodega. */
    public static function idsBodegas()
    {
        if (self::esAdmin()) {
            return null;
        }
        $st = self::staffActual();
        $id = ($st && $st->id_bodega !== null) ? (int) $st->id_bodega : 0;
        return $id > 0 ? [$id] : [-1];
    }

    /** IDs de sucursal permitidas. null = todas (admin). [-1] = el usuario no tiene sucursal. */
    public static function idsSucursales()
    {
        if (self::esAdmin()) {
            return null;
        }
        $st = self::staffActual();
        $id = ($st && $st->id_sucursal !== null) ? (int) $st->id_sucursal : 0;
        return $id > 0 ? [$id] : [-1];
    }

    /** Fragmento SQL " AND <col> IN (...)" para bodega. "" si admin (sin filtro). */
    public static function filtroBodega($col)
    {
        return self::filtroIn($col, self::idsBodegas());
    }

    /** Fragmento SQL " AND <col> IN (...)" para sucursal. "" si admin (sin filtro). */
    public static function filtroSucursal($col)
    {
        return self::filtroIn($col, self::idsSucursales());
    }

    private static function filtroIn($col, $ids)
    {
        if ($ids === null) {
            return '';
        }
        $limpios = array_map('intval', $ids);
        return ' AND ' . $col . ' IN (' . implode(',', $limpios) . ')';
    }
}
