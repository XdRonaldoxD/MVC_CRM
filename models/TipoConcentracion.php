<?php
use Illuminate\Database\Eloquent\Model;
class TipoConcentracion extends Model
{
    protected $table = "tipo_concentracion";
    protected $primaryKey = 'id_tipo_concentracion';
    protected $fillable = [
        'id_tipo_concentracion',
        'id_unidad',
        'glosa_tipo_concentracion',
        'orden_tipo_concentracion',
        'vigente_tipo_concentracion',
        
    ];
    public $timestamps = false;
}
