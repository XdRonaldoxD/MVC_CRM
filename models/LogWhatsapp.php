<?php

use Illuminate\Database\Eloquent\Model;

class LogWhatsapp extends Model
{
    protected $table = "log_whatsapp";
    public $timestamps = false;
    protected $fillable = [
        'id_log_whatsapp',
        'id_usuario',
        'id_cliente',
        'fechacreacion_log_whatsapp',
        'documento_log_whatsapp',
        'numero_documento_log_whatsapp'
    ];
    protected $primaryKey = 'id_log_whatsapp';
}
