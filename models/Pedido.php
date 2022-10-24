<?php
use Illuminate\Database\Eloquent\Model;
class Pedido extends Model
{
    protected $table = "pedido";
    public $timestamps = false;
    protected $fillable = [
        'id_pedido',
        'id_usuario',
        'id_folio',
        'id_cliente',
        'id_sucursal',
        'id_estado_pedido',
        'id_estado_pago',
        'id_estado_preparacion',
        'idProvincia',
        'id_comuna',
        'fechacreacion_pedido',
        'numero_pedido',
        'valorneto_pedido',
        'valortransporte_pedido',
        'descuento_pedido',
        'porcentajeiva_pedido',
        'iva_pedido',
        'valortotal_pedido',
        'retiroentienda_pedido',
        'peso_pedido',
        'nota_pedido',
        'vigente_pedido',
        'rutfactura_pedido',
        'razonsocialfactura_pedido',
        'girofactura_pedido',
        'nombrefactura_pedido',
        'apellidosfactura_pedido',
        'direccionfactura_pedido',
        'comunafactura_pedido',
        'telefonofactura_pedido',
        'direccionenvio_pedido',
        'comunaenvio_pedido',
        'correoenvio_pedido',
        'telefonoenvio_pedido',
        'tipodocumento_pedido',
        'notaprivada_pedido'
        
    ];
    protected $primaryKey = 'id_pedido';
}
