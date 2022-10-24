<?php
use Illuminate\Database\Eloquent\Model;

class ProductoColor extends Model
{
    protected $table = "producto_color";
    protected $primaryKey = 'id_producto_color';
    protected $fillable = [
        'id_producto',
        'nombre_producto_color',
        'hexadecimal_producto_color'
    ];

    public $timestamps = false;
}
