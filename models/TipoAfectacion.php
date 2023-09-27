<?php
use Illuminate\Database\Eloquent\Model;
class TipoAfectacion extends Model
{
    protected $table = "tipo_afectacion";
    protected $primaryKey = 'id_tipo_afectacion';
    protected $fillable = [
        'id_tipo_afectacion',
        'codigo',
        'descripcion',
        'codigo_afectacion',
        'nombre_afectacion',
        'tipo_afectacion',
    ];
    public $timestamps = false;
}
