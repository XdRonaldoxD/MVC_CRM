<?php

require_once "models/ConsultaGlobal.php";
require_once "models/Modulo.php";
require_once "models/PerfilModulo.php";
require_once "models/Perfil.php";
require_once "Helpers/JwtAuth.php";

class PermisoController
{
    // Catálogo de módulos (activos) + perfiles asignables (todos menos ADMINISTRADOR).
    public function listar()
    {
        $modulos = Modulo::where('vigente_modulo', 1)->orderBy('orden_modulo')->get();
        // Solo perfiles ACTIVOS y distintos del ADMINISTRADOR (que tiene acceso total).
        $perfiles = Perfil::where('id_perfil', '!=', 1)
            ->where('vigente_perfil', 1)
            ->orderBy('glosa_perfil')
            ->get();
        echo json_encode(['modulos' => $modulos, 'perfiles' => $perfiles]);
    }

    // IDs de módulos asignados a un perfil (para marcar los checkbox).
    public function modulosPorPerfil()
    {
        $id_perfil = isset($_GET['id_perfil']) ? (int) $_GET['id_perfil'] : 0;
        $ids = PerfilModulo::where('id_perfil', $id_perfil)
            ->where('vigente_perfil_modulo', 1)
            ->pluck('id_modulo');
        echo json_encode($ids);
    }

    // Reemplaza la asignación de módulos de un perfil. El admin (1) no se toca (acceso total).
    public function guardar()
    {
        $datos = json_decode(file_get_contents("php://input"));
        $id_perfil = isset($datos->id_perfil) ? (int) $datos->id_perfil : 0;
        $modulos = (isset($datos->modulos) && is_array($datos->modulos)) ? $datos->modulos : [];
        if ($id_perfil <= 0) {
            http_response_code(400);
            echo json_encode("Perfil inválido.");
            return;
        }
        if ($id_perfil === 1) {
            http_response_code(400);
            echo json_encode("El ADMINISTRADOR tiene acceso total; no se configura.");
            return;
        }
        PerfilModulo::where('id_perfil', $id_perfil)->delete();
        foreach ($modulos as $idm) {
            PerfilModulo::create([
                'id_perfil' => $id_perfil,
                'id_modulo' => (int) $idm,
                'vigente_perfil_modulo' => 1,
            ]);
        }
        echo json_encode("Permisos actualizados");
    }

    // Módulos (claves/rutas) que puede ver el usuario ACTUAL. El id_perfil se toma del
    // JWT (no es manipulable por el cliente). El ADMINISTRADOR ve todos.
    public function misModulos()
    {
        $token = $this->tokenActual();
        $identity = $token ? (new JwtAuth())->checktoken($token, true) : null;
        $id_perfil = (is_object($identity) && isset($identity->id_perfil)) ? (int) $identity->id_perfil : 0;

        if ($id_perfil === 1) {
            $claves = Modulo::where('vigente_modulo', 1)->pluck('link_modulo');
            echo json_encode(['es_admin' => true, 'modulos' => $claves]);
            return;
        }
        $claves = PerfilModulo::join('modulo', 'modulo.id_modulo', 'perfil_modulo.id_modulo')
            ->where('perfil_modulo.id_perfil', $id_perfil)
            ->where('perfil_modulo.vigente_perfil_modulo', 1)
            ->where('modulo.vigente_modulo', 1)
            ->pluck('modulo.link_modulo');
        echo json_encode(['es_admin' => false, 'modulos' => $claves]);
    }

    private function tokenActual()
    {
        $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
        if (isset($_GET['Authorization'])) {
            return $_GET['Authorization'];
        }
        if (isset($headers['Authorization'])) {
            return $headers['Authorization'];
        }
        if (isset($headers['authorization'])) {
            return $headers['authorization'];
        }
        return null;
    }
}
