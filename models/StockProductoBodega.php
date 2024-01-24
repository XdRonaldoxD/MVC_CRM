<?php
use Illuminate\Database\Eloquent\Model;
class StockProductoBodega extends Model
{
    protected $table = "stock_producto_bodega";
    protected $primaryKey = 'id_stock_producto_bodega';

    protected $fillable = [
        'id_stock_producto_bodega',
        'id_producto',
        'id_bodega',
        'total_stock_producto_bodega',
        'totalcritico_stock_producto_bodega',
        'stockentransito_stock_producto_bodega',
        'saldocantidad_stock_producto_bodega',
        'ultimopreciocompra_stock_producto_bodega',
        'precioventa_stock_producto_bodega'
    ];
    public $timestamps = false;
}
