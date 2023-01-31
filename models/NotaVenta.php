<?php
use Illuminate\Database\Eloquent\Model;
class NotaVenta extends Model
{
    protected $table = "nota_venta";
    public $timestamps = false;
    protected $fillable = [
        'id_nota_venta',
        'id_negocio',
        'id_usuario',
        'id_folio',
        'numero_nota_venta',
        'fechacreacion_nota_venta',
        'fechavencimiento_nota_venta',
        'valor_nota_venta',
        'iva_nota_venta',
        'total_nota_venta',
        'estado_nota_venta',
        'saldo_nota_venta',
        'urlpdf_nota_venta',
        'urlticket_nota_venta',
        'escotizacion_nota_venta'
        
    ];
    protected $primaryKey = 'id_nota_venta';
}
