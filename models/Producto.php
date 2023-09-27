<?php
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto";
    protected $primaryKey = 'id_producto';
    protected $fillable = [
        'id_producto',
        'id_tipo_producto',
        'id_tipo_concentracion',
        'id_tipo_inventario',
        'id_unidad',
        'id_marca',
        'id_proveedor',
        'id_tipo_afectacion',
        'codigo_producto',
        'glosa_producto',
        'detalle_producto',
        'detallelargo_producto',
        'multidosis_producto',
        'dosis_producto',
        'concentracion_producto',
        'cantidad_producto',
        'stock_producto',
        'precioventa_producto',
        'preciocosto_producto',
        'fechacreacion_producto',
        'saldocantidad_producto',
        'contenidomultidosis_producto',
        'urlamigable_producto',
        'vigente_producto',
        'visibleonline_producto'
    ];

    public $timestamps = false;
}
