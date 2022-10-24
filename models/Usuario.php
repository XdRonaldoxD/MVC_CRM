<?php

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    public $timestamps = false;
    protected $primaryKey = 'id_usuario';
    protected $fillable = [
        'id_tipo_usuario',
        'id_staff',
        'id_perfil',
        'id_cliente',
        'password_usuario',
        'fechacreacion_usuario',
        'ultimo_acceso_usuario',
        'vigente_usuario',
        'session_id',
        'permisoabrirnegocio_usuario',
        'pathfoto_usuario',
    ];
}
