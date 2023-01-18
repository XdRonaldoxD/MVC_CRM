<?php
use Illuminate\Database\Eloquent\Model;
class TipoProductos extends Model
{
    protected $table = "tipo_producto";
    protected $primaryKey = 'id_tipo_producto';
    protected $fillable = [
        'id_tipo_producto',
        'glosa_tipo_producto',
        'orden_tipo_producto',
        'vigente_tipo_producto'
    ];
    public $timestamps = false;
}
