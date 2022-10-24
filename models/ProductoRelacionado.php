<?php
use Illuminate\Database\Eloquent\Model;
class ProductoRelacionado extends Model
{
    protected $table="producto_relacionado";
    public $timestamps = false;
    protected $primaryKey = 'id_producto_relacionado';
    protected $fillable = [
        'id_producto_relacionado',
        'id_producto',
        'idproductopadre_producto_relacionado',
        'order_producto_relacionado',
        'vigente_producto_relacionado'
    ];
}