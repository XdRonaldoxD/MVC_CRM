<?php
use Illuminate\Database\Eloquent\Model;
class ProductoEspecificaciones extends Model
{
    protected $table="especificaciones_producto";
    public $timestamps = false;
    protected $primaryKey = 'id_especificaciones_producto';
    protected $fillable = [
        'id_especificaciones_producto',
        'id_producto',
        'glosa_especificaciones_producto',
        'respuesta_especificaciones_producto',
        'vigente_especificaciones_producto'
    ];
}