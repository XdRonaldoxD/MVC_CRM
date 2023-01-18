<?php
use Illuminate\Database\Eloquent\Model;
class Factura extends Model
{
    protected $table = "factura";
    protected $primaryKey = 'id_factura';
    protected $fillable = [
        'id_factura',
        'id_cliente',
        'id_folio',
        'id_negocio',
        'id_usuario',
        'numero_factura',
        'serie_factura',
        'fechacreacion_factura',
        'valorafecto_factura',
        'valorexento_factura',
        'iva_factura',
        'total_factura',
        'fechavencimiento_factura',
        'estado_factura',
        'urlpdf_factura',
        'urlxml_factura',
        'path_documento',
        'saldo_factura',
        'path_ticket_pos',
        'path_ticket_factura',
        'xml_factura',
        'cdrzip_factura'
        
    ];
    public $timestamps = false;
}
