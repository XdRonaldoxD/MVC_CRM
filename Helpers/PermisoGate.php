<?php

require_once "config/database_mysql.php";

/**
 * [PERMISOS] Autorización por perfil a nivel de backend (defensa en profundidad).
 *
 * Cada controller de administración se mapea a los módulos (claves link_modulo) que
 * lo usan legítimamente. Se permite si el perfil tiene AL MENOS UNO de esos módulos.
 * - El ADMINISTRADOR (id_perfil = 1) tiene acceso total.
 * - Empresa / Permiso: solo administrador.
 * - Controllers NO listados (Cliente, Pusher, Usuario/login, Staff, lookups, etc.) son
 *   compartidos/utilitarios y quedan permitidos para cualquier usuario autenticado, para
 *   no romper flujos transversales (POS, formularios de producto, chat, login).
 */
class PermisoGate
{
    private static $mapa = [
        'Sucursal'             => ['SUCURSAL'],
        'Bodega'               => ['BODEGAS'],
        'Marca'                => ['MARCAS', 'PRODUCTOS'],
        'Categoria'            => ['CATEGORIAS', 'PRODUCTOS'],
        'Producto'             => ['PRODUCTOS'],
        'NuevoProducto'        => ['PRODUCTOS'],
        'Atributo'             => ['ATRIBUTOS', 'PRODUCTOS'],
        'ProductoExcel'        => ['PRODUCTOS', 'REPORTE PRODUCTOS'],
        'NotaVenta'            => ['PAGO NOTA VENTA', 'ANULAR DOCUMENTOS'],
        'Negocio'              => ['PAGO NOTA VENTA', 'VENTAS', 'CAJA', 'ANULAR DOCUMENTOS'],
        'Venta'                => ['VENTAS', 'CAJA'],
        'Caja'                 => ['CAJA'],
        'AperturaCaja'         => ['CAJA'],
        'AnularDocumento'      => ['ANULAR DOCUMENTOS'],
        'ReporteVentaProducto' => ['REPORTE VENTA PRODUCTO'],
        'LibroVentas'          => ['LIBRO VENTAS', 'REPORTE PRODUCTOS'],
        'Kardex'               => ['KARDEX'],
        'Pedido'               => ['PEDIDOS'],
        'Slider'               => ['SLIDER'],
        'Promociones'          => ['PROMOCIONES'],
    ];

    private static $soloAdmin = ['Empresa', 'Permiso'];

    public static function permitido($id_perfil, $controller)
    {
        $id_perfil = (int) $id_perfil;
        if ($id_perfil === 1) {
            return true;
        }
        if (in_array($controller, self::$soloAdmin, true)) {
            return false;
        }
        if (!isset(self::$mapa[$controller])) {
            return true; // compartido / utilitario / lookup
        }
        $requeridos = self::$mapa[$controller];
        $claves = self::clavesDePerfil($id_perfil);
        foreach ($requeridos as $r) {
            if (in_array($r, $claves, true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * ¿El perfil tiene asignada una clave de módulo concreta? El ADMINISTRADOR siempre.
     * Útil para capacidades extra (no de menú), p.ej. 'VER TODAS LAS CAJAS'.
     */
    public static function perfilTiene($id_perfil, $clave)
    {
        if ((int) $id_perfil === 1) {
            return true;
        }
        return in_array($clave, self::clavesDePerfil($id_perfil), true);
    }

    private static function clavesDePerfil($id_perfil)
    {
        try {
            $db = database::conectar();
            $stmt = $db->prepare("SELECT m.link_modulo
                FROM perfil_modulo pm
                INNER JOIN modulo m ON m.id_modulo = pm.id_modulo
                WHERE pm.id_perfil = ? AND pm.vigente_perfil_modulo = 1 AND m.vigente_modulo = 1");
            $stmt->execute([$id_perfil]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (\Throwable $e) {
            return [];
        }
    }
}
