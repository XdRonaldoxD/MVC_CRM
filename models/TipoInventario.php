<?php
use Illuminate\Database\Eloquent\Model;
class TipoInventario extends Model
{

    protected $table = 'tipo_inventario';
    public $timestamps = false;

    protected $primaryKey = 'id_tipo_inventario';

    protected $fillable = [
        'id_tipo_inventario',
        'glosa_tipo_inventario',
        'ventaproducto_tipo_inventario',
        'vigente_tipo_inventario'
    ];
}
