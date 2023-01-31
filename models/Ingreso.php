<?php

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table="ingreso";
    public $timestamps = false;
    protected $fillable = [
        'id_ingreso',
        'id_negocio',
        'id_institucion_financiera',
        'id_folio',
        'id_comprobante_ingreso',
        'id_medio_pago',
        'id_caja',
        'id_tipo_ingreso',
        'valor_ingreso',
        'numero_ingreso',
        'comentario_ingreso',
        'fechavencimiento_ingreso',
        'estado_ingreso',
        'documento',
        'fechacreacion_ingreso',
        'fechapago',
        'mediopagoonline_ingreso',
        'fechatransferencia_ingreso',
        'ruttransferencia_ingreso',
        'numero_transbank'
    ];
    protected $primaryKey = 'id_ingreso';
}