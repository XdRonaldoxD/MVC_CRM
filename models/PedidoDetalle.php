<?php
use Illuminate\Database\Eloquent\Model;
class PedidoDetalle extends Model
{
    protected $table = "pedido_detalle";
    public $timestamps = false;
    protected $fillable = [
        'id_pedido',
        'id_boleta',
        'id_producto',
        'id_negocio',
        'id_lista_precio',
        'valorneto_pedido_detalle',
        'iva_pedido_detalle',
        'descuento_pedido_detalle',
        'valortotal_pedido_detalle',
        'cantidad_pedido_detalle',
        'fechacreacion_pedido_detalle',
        'orden_pedido_detalle',
    ];
    protected $primaryKey = 'id_pedido_detalle';
}
