<?php

use Illuminate\Database\Eloquent\Model;
class Negocio extends Model
{
    protected $table = "negocio";
    protected $primaryKey = 'id_negocio';
    protected $fillable = [
        'id_negocio',
        'id_apertura_caja',
        'id_usuario',
        'id_folio',
        'id_ficha_paciente',
        'id_cliente',
        'id_sucursal',
        'id_bodega',
        'id_pedido',
        'id_descuento',
        'fechacreacion_negocio',
        'numero_negocio',
        'valor_negocio',
        'descuento_negocio',
        'totalrecargo_negocio',
        'totalrecargogeneral_negocio',
        'recargo_negocio',
        'porcentajeiva_negocio',
        'estado_negocio',
        'valorexento_negocio',
        'valorafecto_negocio',
        'cerrado_negocio',
        'pos_negocio',
        'vigente_negocio',
        'efectivo_negocio',
        'vuelto_negocio'
        
    ];
    public $timestamps = false;
}
