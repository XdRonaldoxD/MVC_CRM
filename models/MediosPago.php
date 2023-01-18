<?php

use Illuminate\Database\Eloquent\Model;

class MediosPago extends Model
{
    protected $table = 'medio_pago';
    public $timestamps = false;
    protected $primaryKey = 'id_medio_pago';
    protected $fillable = [
        'id_medio_pago',
        'glosa_medio_pago',
        'subsidio_medio_pago',
        'orden_medio_pago',
        'vigente_medio_pago'
    ];
}
