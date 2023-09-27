<?php
use Illuminate\Database\Eloquent\Model;
class TipoDocumento extends Model
{
    protected $table = "tipo_documento";
    protected $primaryKey = 'id_tipo_documento';
    protected $fillable = [
        'id_tipo_documento',
        'glosa_tipo_documento',
        'orden_tipo_documento',
        'vigente_tipo_documento'
    ];
    public $timestamps = false;
}
