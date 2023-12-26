<?php

use Illuminate\Database\Eloquent\Model;

class ProductoHistorial extends Model
{
    protected $table = 'producto_historial';
    public $timestamps = false;
    protected $primaryKey = 'id_producto_historial';
    protected $fillable = [
        'id_producto_historial',
        'id_usuario',
        'id_tipo_movimiento',
        'id_producto',
        'id_proveedor',
        'id_bodega',
        'cantidadmovimiento_producto_historial',
        'fecha_producto_historial',
        'comentario_producto_historial',
        'preciocompra_producto_historial',
        'id_tipo_documento',
        'numerotipodocumento_producto_historial'
    ];
}
