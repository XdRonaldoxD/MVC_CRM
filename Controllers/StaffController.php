<?php
require_once "models/Usuario.php";
require_once "models/Perfil.php";
require_once "models/Staff.php";
require_once "models/ConsultaGlobal.php";
class StaffController
{
    public function MostrarStaff()
    {
        if (isset($_POST['id_usuario'])) {
            $Staff = Usuario::join('staff', 'staff.id_staff', 'usuario.id_staff')
                ->where('id_usuario', $_POST['id_usuario'])
                ->select('staff.*', 'usuario.id_perfil')
                ->first();
        } else {
            $Staff = json_encode(null);
        }

        $Perfil = Perfil::where('vigente_perfil', 1)->get();
        $respuesta = [
            "Staff" => $Staff,
            "Perfil" => $Perfil
        ];

        echo json_encode($respuesta);
    }

    public function GestionarStaff()
    {
        $InformacionStaff = json_decode($_POST['InformacionStaff']);
        $staff = [
            'dni_staff' => $InformacionStaff->dni_staff ?? null,
            'nombre_staff' => $InformacionStaff->nombre_staff,
            'apellidopaterno_staff' => $InformacionStaff->apellidopaterno_staff,
            'apellidomaterno_staff' => $InformacionStaff->apellidomaterno_staff,
            'e_mail_staff' => $InformacionStaff->e_mail_staff,
            'telefono_staff' => $InformacionStaff->telefono_staff,
            'celular_staff' => $InformacionStaff->celular_staff,
            'sexo_staff' => $InformacionStaff->sexo_staff
        ];
        if (!empty($InformacionStaff->id_staff)) {
            Staff::where('id_staff', $InformacionStaff->id_staff)->update($staff);
            $idstaff=$InformacionStaff->id_staff;
            $respuesta = "Actualizado";
        } else {
            $staff += [
                'vigente_usuario' => 1
            ];
            $staff=Staff::create($staff);
            $idstaff=$staff->id_staff;
            $respuesta = "Creado";
        }
        $claveactualizado = [
            'id_perfil' => $InformacionStaff->id_perfil
        ];
        if (!empty($InformacionStaff->newPassword) && !empty($InformacionStaff->confirmPassword)) {
            $pwd = hash('sha256', $InformacionStaff->newPassword);
            $claveactualizado += [
                'password_usuario' => $pwd
            ];
        }
        if ($InformacionStaff->id_usuario) {
            Usuario::where('id_staff', $idstaff)->update($claveactualizado);
        }else{
            $claveactualizado+=[
                'id_staff'=>$idstaff,
                "fechacreacion_usuario"=>date('Y-m-d H:i:s'),
                "vigente_usuario"=>1,
            ];
            Usuario::create($claveactualizado);
        }
        echo json_encode($respuesta);
    }

    public function updatepass()
    {
        if (!empty($_POST['newPassword']) && !empty($_POST['confirmPassword'])) {
            $pwd = hash('sha256', $_POST['newPassword']);
            $claveactualizado = [
                'password_usuario' => $pwd
            ];
            Usuario::where('id_usuario', $_POST['id_usuario'])->update($claveactualizado);
            echo json_encode("Actualizado");
        }
    }
    public function mostrarPerfiles()
    {
        $perfiles = Perfil::where('vigente_perfil', 1)->get();
        echo json_encode($perfiles);
    }


    public function listUserActive()
    {
        $datosPost = file_get_contents("php://input");
        $datosPost = json_decode($datosPost);
        if ($datosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $datosPost->length;
        }
        $buscar = $datosPost->search->value;
        $consulta = " and (CONCAT(nombre_staff,' ',apellidopaterno_staff,' ',apellidomaterno_staff) LIKE '%$buscar%' or
        telefono_staff LIKE '%$buscar%' or celular_staff LIKE '%$buscar%' or e_mail_staff  LIKE '%$buscar%' or
        glosa_perfil  LIKE '%$buscar%' )";
        $query = "SELECT
        id_usuario,
        id_perfil,
        usuario.id_staff,
        dni_staff,
        nombre_staff,
        apellidopaterno_staff,
        apellidomaterno_staff,
        e_mail_staff,
        telefono_staff,
        celular_staff,
        sexo_staff,
        glosa_perfil
        FROM usuario
        inner join staff using (id_staff)
        left join perfil using (id_perfil)
        WHERE  vigente_usuario=$datosPost->vigente_usuario $consulta
        order by usuario.id_usuario desc";
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

    public function gestionActivoDesactivado()
    {
        if ($_POST['accion'] === 'ACTIVAR') {
            $data = [
                'vigente_usuario' => 1
            ];
        } else {
            $data = [
                'vigente_usuario' => 0
            ];
        }
        Usuario::where("id_usuario", $_POST['id_usuario'])->update($data);
        echo json_encode("exitoso");
    }

    public function correoEdicionUsuarioEnUso()
    {
        $usuario = Staff::where('e_mail_staff', $_GET['email_usuario']);
        if (!empty($_GET['id_staff'])) {
            $usuario = $usuario->where("id_staff", '!=', $_GET['id_staff']);
        }
        $usuario = $usuario->exists();
        echo json_encode($usuario);
    }
}
