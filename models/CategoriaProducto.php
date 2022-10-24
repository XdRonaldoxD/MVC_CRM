<?php

use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    protected $table = 'categoria_producto';
    public $timestamps = false;
    protected $primaryKey = 'id_categoria_producto';
    protected $fillable = [
        'id_categoria_producto',
        'id_categoria',
        'id_producto'
    ];
}
