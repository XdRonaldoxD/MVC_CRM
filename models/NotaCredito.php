<?php
use Illuminate\Database\Eloquent\Model;
class NotaCredito extends Model
{
    protected $table = "nota_credito";
    public $timestamps = false;
    protected $fillable = [
        'id_nota_credito',
        'id_folio',
        'id_boleta',
        'id_factura',
        'id_usuario',
        'numero_nota_credito',
        'serie_nota_credito',
        'fechacreacion_nota_credito',
        'valorafecto_nota_credito',
        'valorexento_nota_credito',
        'iva_nota_credito',
        'total_nota_credito',
        'estado_nota_credito',
        'zip_nota_credito',
        'xml_nota_credito',
        'path_nota_credito',
        'path_ticket_nota_credito',
        'id_motivo_devolucion',
        'respuesta_sunat_nota_credito'
    ];
    protected $primaryKey = 'id_nota_credito';
}
