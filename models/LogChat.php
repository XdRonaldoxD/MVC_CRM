<?php

use Illuminate\Database\Eloquent\Model;

class Logchat extends Model
{
    protected $table="log_chat";
    public $timestamps = false;
    protected $fillable = [
        'id_usuario',
        'nombre_log_chat',
        'email_log_chat',
        'telefono_log_chat',    
        'fechacreacion_log_chat',
        'conversacion_log_chat',
        'estado_log_chat',
        'identificadorcliente_log_chat',
        'estado_linea_log_chat'
    ];
    protected $primaryKey = 'id_log_chat';
}