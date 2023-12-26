<?php

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = 'bodega';
    public $timestamps = false;
    protected $primaryKey = 'id_bodega';
    protected $fillable = [
        'id_bodega',
        'codigo_bodega',
        'glosa_bodega',
        'vigente_bodega'
    ];
}
