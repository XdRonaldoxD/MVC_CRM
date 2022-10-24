<?php
use Illuminate\Database\Eloquent\Model;
class ProductoImagen extends Model
{
    protected $table="producto_imagen";
    public $timestamps = false;
    protected $primaryKey = 'id_producto_imagen';
    protected $fillable = [
        'id_producto_imagen',
        'id_producto',
        'nombre_producto_imagen',
        'extension_producto_imagen',
        'peso_producto_imagen',
        'path_producto_imagen',
        'fechacreacion_producto_imagen',
        'estado_producto_imagen',
        'keytemp_producto_imagen',
        'orden_producto_imagen',
        'portada_producto_imagen'
    ];
}