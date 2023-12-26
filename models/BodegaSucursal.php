<?php

use Illuminate\Database\Eloquent\Model;

class BodegaSucursal extends Model
{
    protected $table = 'bodega_sucursal';
    public $timestamps = false;
    protected $primaryKey = 'id_bodega';
    protected $fillable = [
        'id_bodega_sucursal',
        'id_sucursal',
        'id_bodega'
    ];
}
