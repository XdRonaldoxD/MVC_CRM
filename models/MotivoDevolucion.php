<?php

use Illuminate\Database\Eloquent\Model;

class MotivoDevolucion extends Model
{
    protected $table = 'motivo_devolucion';
    public $timestamps = false;
    protected $primaryKey = 'id_motivo_devolucion';
    protected $fillable = [
        'id_motivo_devolucion',
        'glosa_motivo_devolucion',
        'afectacaja_motivo_devolucion',
        'orden_motivo_devolucion',
        'vigente_motivo_devolucion'
    ];
}
