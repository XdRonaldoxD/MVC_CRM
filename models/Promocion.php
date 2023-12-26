<?php

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = "promocion";
    public $timestamps = false;
    protected $primaryKey = 'id_promocion';
    protected $fillable = [
        'id_promocion',
        'titulo_promocion',
        'fecha_promocion',
        'descripcion_promocion',
        'url_promocion',
        'id_url_promocion',
        'fecha_creacion_promocion'
    ];
}
