<?php
use Illuminate\Database\Eloquent\Model;
class PedidoDetalleAtributoProducto extends Model
{
    protected $table = "pedido_detalle_atributo_producto";
    public $timestamps = false;
    protected $fillable = [
        'id_pedido_detalle',
        'id_atributo',
        'hexadecimal_producto_color',
        'cantidad_pedido_detalle_atributo_producto',
        'nombre_color_detalle_atributo_producto'
    ];
    protected $primaryKey = 'id_pedido_detalle_atributo';
}
