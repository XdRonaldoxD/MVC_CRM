<?php

use Illuminate\Database\Eloquent\Model;

class CertificadoDigital extends Model
{
    protected $table = 'certificado_digital_empresa';
    public $timestamps = false;
    protected $primaryKey = 'id_certificado_digital';
    protected $fillable = [
        'id_certificado_digital',
        'id_empresa_venta_online',
        'usuariosol_certificado_digital',
        'clavesol_certificado_digital',
        'clavearchivo_certificado_digital',
        'path_certificado_digital',
        'nombre_certificado_digital',
        'fechainicio_certificado_digital',
        'fechafin_certificado_digital',
        'fechacreacion_certificado_digital',
        'uso_certificado_digital'
    ];
}
