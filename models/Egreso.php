<?php

use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    protected $table = 'egreso';
    public $timestamps = false;
    protected $primaryKey = 'id_egreso';
    protected $fillable = [
        'id_egreso',
        'id_caja',
        'id_folio',
        'id_staff',
        'id_negocio',
        'id_tipo_egreso',
        'numero_egreso',
        'fechacreacion_egreso',
        'valor_egreso',
        'comentario_egreso',
        'responsable_egreso',
        'estado_egreso',
        'path_egreso'
    ];
}
