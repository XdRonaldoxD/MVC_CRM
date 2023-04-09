<?php
require_once "models/Usuario.php";
require_once "models/Perfil.php";
require_once "models/Staff.php";
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
            $respuesta = "Actualizado";
        } else {
            Staff::create($staff);
            $respuesta = "Creado";
        }

        $clave_actualizado = [
            'id_perfil' => $InformacionStaff->id_perfil
        ];
        if (!empty($InformacionStaff->newPassword) && !empty($InformacionStaff->confirmPassword)) {
            $pwd = hash('sha256', $InformacionStaff->newPassword);
            $clave_actualizado += [
                'password_usuario' => $pwd
            ];
        }
        Usuario::where('id_staff', $InformacionStaff->id_staff)->update($clave_actualizado);
        echo json_encode($respuesta);
    }
}
