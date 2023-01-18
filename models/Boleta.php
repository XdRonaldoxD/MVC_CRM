<?php
use Illuminate\Database\Eloquent\Model;
class Boleta extends Model
{
    protected $table = "boleta";
    protected $primaryKey = 'id_boleta';
    protected $fillable = [
        'id_boleta',
        'id_negocio',
        'id_cliente',
        'id_usuario',
        'id_folio',
        'numero_boleta',
        'serie_boleta',
        'valor_boleta',
        'fechacreacion_boleta',
        'fechavencimiento_boleta',
        'iva_boleta',
        'total_boleta',
        'urlpdf_boleta',
        'xml_boleta',
        'cdrzip_boleta',
        'path_boleta',
        'path_ticket_boleta',
        'estado_boleta',
        'saldo_boleta',
        'path_ticket_pos',
        'comentario_boleta'
    ];
    public $timestamps = false;
}
