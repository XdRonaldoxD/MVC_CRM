<?php

require_once "models/ConsultaGlobal.php";
require_once "Helpers/JwtAuth.php";

/**
 * [DASHBOARD] KPIs para la pantalla de inicio. Todo se calcula en una sola petición.
 * Seguridad: el id_perfil / id_usuario se derivan del JWT (no del cliente).
 *  - ADMINISTRADOR (id_perfil=1): ve datos GLOBALES + ranking por vendedor.
 *  - Otros perfiles: solo ven SUS propias ventas.
 * Los montos usan negocio.valor_negocio (valor de la venta).
 */
class DashboardController
{
    public function resumen()
    {
        $identity = $this->identidad();
        $esAdmin = (is_object($identity) && isset($identity->id_perfil) && (int) $identity->id_perfil === 1);
        $idUsuario = (is_object($identity) && isset($identity->sub)) ? (int) $identity->sub : 0;

        if (!is_object($identity)) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'No autorizado.']);
            return;
        }

        // Un no-admin solo ve SUS propias ventas. El id viene del JWT (entero confiable).
        $scopeSimple = $esAdmin ? '' : ' AND id_usuario = ' . $idUsuario;
        $scopeNeg = $esAdmin ? '' : ' AND negocio.id_usuario = ' . $idUsuario;

        $cg = new ConsultaGlobal();

        // --- Tarjetas: hoy y mes actual ---
        $sqlCards = "SELECT
            COALESCE(SUM(CASE WHEN DATE(fechacreacion_negocio)=CURDATE() THEN valor_negocio END),0) AS ventas_hoy,
            COUNT(CASE WHEN DATE(fechacreacion_negocio)=CURDATE() THEN 1 END) AS cantidad_hoy,
            COALESCE(SUM(CASE WHEN YEAR(fechacreacion_negocio)=YEAR(CURDATE()) AND MONTH(fechacreacion_negocio)=MONTH(CURDATE()) THEN valor_negocio END),0) AS ventas_mes,
            COUNT(CASE WHEN YEAR(fechacreacion_negocio)=YEAR(CURDATE()) AND MONTH(fechacreacion_negocio)=MONTH(CURDATE()) THEN 1 END) AS cantidad_mes
            FROM negocio
            WHERE vigente_negocio=1 $scopeSimple";
        $rowsCards = $cg->ConsultaGlobal($sqlCards);
        $c = isset($rowsCards[0]) ? $rowsCards[0] : null;
        $cantidadMes = $c ? (int) $c->cantidad_mes : 0;
        $ventasMes = $c ? (float) $c->ventas_mes : 0;

        // --- Ventas por mes (últimos 12 meses, rellenando los vacíos con 0) ---
        $sqlMes = "SELECT DATE_FORMAT(fechacreacion_negocio,'%Y-%m') AS ym, ROUND(SUM(valor_negocio),2) AS total
            FROM negocio
            WHERE vigente_negocio=1
              AND fechacreacion_negocio >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH),'%Y-%m-01')
              $scopeSimple
            GROUP BY ym";
        $rowsMes = $cg->ConsultaGlobal($sqlMes);
        $mapMes = [];
        foreach ($rowsMes as $r) {
            $mapMes[$r->ym] = (float) $r->total;
        }
        $nombresMes = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
        $porMesLabels = [];
        $porMesData = [];
        $base = new DateTime('first day of this month');
        for ($i = 11; $i >= 0; $i--) {
            $d = (clone $base)->modify("-$i month");
            $key = $d->format('Y-m');
            $porMesLabels[] = $nombresMes[(int) $d->format('n') - 1] . ' ' . $d->format('Y');
            $porMesData[] = isset($mapMes[$key]) ? $mapMes[$key] : 0;
        }

        // --- Ventas por usuario (mes actual) — SOLO admin ---
        $porUsuario = ['labels' => [], 'data' => []];
        if ($esAdmin) {
            $sqlUsr = "SELECT TRIM(CONCAT(IFNULL(staff.nombre_staff,''),' ',IFNULL(staff.apellidopaterno_staff,''))) AS vendedor,
                ROUND(SUM(negocio.valor_negocio),2) AS total
                FROM negocio
                INNER JOIN usuario ON usuario.id_usuario=negocio.id_usuario
                LEFT JOIN staff ON staff.id_staff=usuario.id_staff
                WHERE negocio.vigente_negocio=1
                  AND YEAR(fechacreacion_negocio)=YEAR(CURDATE()) AND MONTH(fechacreacion_negocio)=MONTH(CURDATE())
                GROUP BY negocio.id_usuario
                ORDER BY total DESC";
            $rowsUsr = $cg->ConsultaGlobal($sqlUsr);
            foreach ($rowsUsr as $r) {
                $porUsuario['labels'][] = ($r->vendedor !== '' && $r->vendedor !== null) ? $r->vendedor : 'Sin nombre';
                $porUsuario['data'][] = (float) $r->total;
            }
        }

        // --- Top productos (mes actual) ---
        $sqlTop = "SELECT producto.glosa_producto AS glosa,
            ROUND(SUM(nd.total_negocio_detalle),2) AS total,
            SUM(nd.cantidad_negocio_detalle) AS cantidad
            FROM negocio_detalle nd
            INNER JOIN producto USING (id_producto)
            INNER JOIN negocio USING (id_negocio)
            WHERE negocio.vigente_negocio=1
              AND YEAR(fechacreacion_negocio)=YEAR(CURDATE()) AND MONTH(fechacreacion_negocio)=MONTH(CURDATE())
              $scopeNeg
            GROUP BY id_producto
            ORDER BY total DESC
            LIMIT 8";
        $rowsTop = $cg->ConsultaGlobal($sqlTop);
        $topLabels = [];
        $topData = [];
        foreach ($rowsTop as $r) {
            $topLabels[] = $r->glosa;
            $topData[] = (float) $r->total;
        }

        echo json_encode([
            'es_admin' => $esAdmin,
            'cards' => [
                'ventas_hoy' => $c ? (float) $c->ventas_hoy : 0,
                'cantidad_hoy' => $c ? (int) $c->cantidad_hoy : 0,
                'ventas_mes' => $ventasMes,
                'cantidad_mes' => $cantidadMes,
                'ticket_promedio' => $cantidadMes > 0 ? round($ventasMes / $cantidadMes, 2) : 0,
            ],
            'por_mes' => ['labels' => $porMesLabels, 'data' => $porMesData],
            'por_usuario' => $porUsuario,
            'top_productos' => ['labels' => $topLabels, 'data' => $topData],
        ]);
    }

    private function identidad()
    {
        $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
        $token = null;
        if (isset($_GET['Authorization'])) {
            $token = $_GET['Authorization'];
        } elseif (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
        } elseif (isset($headers['authorization'])) {
            $token = $headers['authorization'];
        }
        if (!$token) {
            return null;
        }
        return (new JwtAuth())->checktoken($token, true);
    }
}
