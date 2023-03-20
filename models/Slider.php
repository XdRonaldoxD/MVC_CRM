<?php

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'slider';
    public $timestamps = false;
    protected $primaryKey = 'id_slider';
    protected $fillable = [
        'id_slider',
        'id_categoria',
        'nombre_slider',
        'tipoarchivo_slider',
        'peso_slider',
        'fechacreacion_slider',
        'pathmobile_slider',
        'pathescritorio_slider',
        'urlimagen_slider',
        'orden_slider',
        'vigente_slider',
    ];
}
