<?php

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'caja';
    public $timestamps = false;
    protected $primaryKey = 'id_caja';
    protected $fillable = [
        'id_caja',
        'id_sucursal_staff',
        'id_staff',
        'fechacreacion_caja',
        'fechacierre_caja',
        'estado_caja',
        'montoinicial_caja'
    ];
}
