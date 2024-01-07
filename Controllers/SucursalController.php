<?php

require_once "models/Sucursal.php";
require_once "models/Bodega.php";
require_once "models/Departamento.php";
require_once "models/Cliente.php";
require_once "models/BodegaSucursal.php";
require_once "models/ConsultaGlobal.php";
class SucursalController
{
    public function traerSucursal()
    {
        $sucursal = Sucursal::join('distrito', 'distrito.idDistrito', 'sucursal.idDistrito')
            ->join('provincia', 'provincia.idProvincia', 'distrito.idProvincia')
            ->join('departamentos', 'departamentos.idDepartamento', 'provincia.idDepartamento')
            ->leftjoin('cliente', 'cliente.id_cliente', 'sucursal.idclientedefectopos_sucursal')
            ->where('id_sucursal', $_POST['id_sucursal'])
            ->select("sucursal.*", 'departamentos.idDepartamento', 'provincia.idProvincia', 'distrito.idProvincia', 'cliente.id_cliente', 'cliente.nombre_cliente', 'cliente.apellidopaterno_cliente', 'cliente.apellidomaterno_cliente')
            ->first();
        $sucursalbodega = BodegaSucursal::where('id_sucursal', $_POST['id_sucursal'])->get()->toArray();
        $respuesta = [
            "sucursal" => $sucursal,
            "sucursalbodega" => $sucursalbodega
        ];
        echo json_encode($respuesta);
    }

    public function gestionarSucursal()
    {
        $sucursal = json_decode($_POST['sucursal']);
        $bodegas = $sucursal->id_bodega;
        $datos = [
            'idDistrito' => $sucursal->idDistrito,
            'codigo_sucursal' => $sucursal->codigo_sucursal,
            'glosa_sucursal' => $sucursal->glosa_sucursal,
            'encargado_sucursal' => $sucursal->encargado_sucursal,
            'direccion_sucursal' => $sucursal->direccion_sucursal,
            'telefono_sucursal' => $sucursal->telefono_sucursal,
            'e_mail_sucursal' => $sucursal->e_mail_sucursal,
            'mapa_sucursal' => $sucursal->mapa_sucursal,
            'descripcion_sucursal' => $sucursal->descripcion_sucursal,
            'idclientedefectopos_sucursal' => $sucursal->idclientedefectopos_sucursal,
            'idusuarioventaonlinedefecto_sucursal' => $sucursal->idusuarioventaonlinedefecto_sucursal
        ];
        if (!empty($sucursal->id_sucursal)) {
            Sucursal::where('id_sucursal', $sucursal->id_sucursal)->update($datos);
            $id_sucursal = $sucursal->id_sucursal;
            $respuesta = "Sucursal Actualizado";
        } else {
            $datos += [
                'vigente_sucursal' => 1
            ];
            $sucursal = Sucursal::create($datos);
            $id_sucursal = $sucursal->id_sucursal;
            $respuesta = "Sucursal Creado";
        }

        $id_bodegas = BodegaSucursal::where('id_sucursal', $id_sucursal)->pluck('id_bodega')->toArray();
        $eliminamosBodega = array_diff($id_bodegas, $bodegas);
        BodegaSucursal::where('id_sucursal', $id_sucursal)->whereIn('id_bodega', $eliminamosBodega)->delete();
        $agregamosBodega = array_diff($bodegas, $id_bodegas);
        foreach ($agregamosBodega as $id_bodega) {
            $data = [
                'id_sucursal' => $id_sucursal,
                'id_bodega' => $id_bodega
            ];
            BodegaSucursal::create($data);
        }
        echo json_encode($respuesta);
    }

    public function listaSucursal()
    {
        $datosPost = file_get_contents("php://input");
        $datosPost = json_decode($datosPost);
        if ($datosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $datosPost->length;
        }
        $buscar = $datosPost->search->value;
        $consulta = " and (glosa_sucursal LIKE '%$buscar%' or
        direccion_sucursal LIKE '%$buscar%' or e_mail_sucursal LIKE '%$buscar%' or codigo_sucursal  LIKE '%$buscar%' or
        encargado_sucursal  LIKE '%$buscar%' )";
        $query = "SELECT *,
        (
            SELECT GROUP_CONCAT(glosa_bodega) from bodega_sucursal
            inner join bodega using (id_bodega)
            where id_sucursal=sucursal.id_sucursal
        ) as bodegas
        FROM sucursal
        inner join distrito using (idDistrito)
        inner join provincia using (idProvincia)
        inner join departamentos using (idDepartamento)
        WHERE vigente_sucursal=$datosPost->vigente_sucursal $consulta
        order by sucursal.id_sucursal desc";

        $consultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($query);
        $query .= "  LIMIT {$longitud} OFFSET $datosPost->start ";
        $consultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        $datos = array(
            "draw" => $datosPost->draw,
            "recordsTotal" => count($consultaGlobalLimit),
            "recordsFiltered" => count($consultaGlobalLimit),
            "data" => $consultaGlobal
        );
        echo json_encode($datos);
    }

    public function estadoSucursal()
    {
        $data = [
            'vigente_sucursal' => $_POST['vigente_sucursal']
        ];
        Sucursal::where("id_sucursal", $_POST['id_sucursal'])->update($data);
        echo json_encode("exitoso");
    }

    public function traerDatosSucursal()
    {
        $bodegas = Bodega::where('vigente_bodega', 1)->get()->toArray();
        $departamento = Departamento::join('provincia', 'provincia.idDepartamento', 'departamentos.idDepartamento')
            ->join('distrito', 'distrito.idProvincia', 'provincia.idProvincia')
            ->get()->toArray();
        $departamentos = [];
        $provincia = [];
        $distrito = [];
        foreach ($departamento as $item) {
            if (!isset($departamentos[$item['idDepartamento']])) {
                $datos = [
                    "idDepartamento" => $item['idDepartamento'],
                    "departamento" => $item['departamento']
                ];
                $departamentos[$item['idDepartamento']] = $datos;
            }
            if (!isset($provincia[$item['idProvincia']])) {
                $datos = [
                    "idProvincia" => $item['idProvincia'],
                    "provincia" => $item['provincia'],
                    "idDepartamento" => $item['idDepartamento'],
                ];
                $provincia[$item['idProvincia']] = $datos;
            }
            if (!isset($distrito[$item['idDistrito']])) {
                $datos = [
                    "idDistrito" => $item['idDistrito'],
                    "idProvincia" => $item['idProvincia'],
                    "distrito" => $item['distrito'],
                ];
                $distrito[$item['idDistrito']] = $datos;
            }
        }
        $respuesta = [
            "bodegas" => $bodegas,
            'departamentos' => $departamentos,
            'provincia' => $provincia,
            'distrito' => $distrito,
        ];
        echo json_encode($respuesta);
    }
    public function buscarClienteDefecto()
    {
        $buscar = $_GET['search']; // Asegúrate de validar y limpiar esta entrada de usuario.
        $clientes = Cliente::Where(function ($query)  use ($buscar) {
            $query->where('nombre_cliente', 'LIKE', "%$buscar%")
                ->orWhere('apellidopaterno_cliente', 'LIKE', "%$buscar%")
                ->orWhere('apellidomaterno_cliente', 'LIKE', "%$buscar%");
        })
            ->where('vigente_cliente', 1)
            ->select("nombre_cliente", 'id_cliente')
            ->get()
            ->sortBy(function ($item) {
                return [
                    substr($item->nombre_cliente, 0, 1),
                    substr($item->nombre_cliente, -1)
                ];
            })
            ->values()
            ->toArray();
        echo json_encode($clientes);
    }

    public function buscaUsuarioDefecto()
    {
        $buscar = $_GET['search']; // Asegúrate de validar y limpiar esta entrada de usuario.
        $sql = "SELECT CONCAT(nombre_staff,' ',apellidopaterno_staff,' ',apellidomaterno_staff) as  nombre_staff,usuario.id_usuario
            FROM usuario
            JOIN staff ON staff.id_staff = usuario.id_staff
            WHERE (
                nombre_staff LIKE '%$buscar%'
                OR apellidopaterno_staff LIKE '%$buscar%'
                OR apellidomaterno_staff LIKE '%$buscar%'
            )
            AND vigente_usuario = 1
            ORDER BY SUBSTRING(nombre_staff, 1, 1), SUBSTRING(nombre_staff, -1);";
        $staff = (new ConsultaGlobal())->ConsultaGlobal($sql);
        echo json_encode($staff);
    }
}
