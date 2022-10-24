<?php

use Illuminate\Database\Eloquent\Model;

class AtributoProducto extends Model
{
   protected $table = 'atributo_producto';
   public $timestamps = false;
   protected $primaryKey = 'id_atributo_producto';
   protected $fillable = [
      'id_atributo_producto',
      'id_atributo',
      'id_producto',
      'stock_atributo'
   ];
}
