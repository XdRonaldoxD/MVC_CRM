<?php

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
   protected $table = 'categoria';
   public $timestamps = false;
   protected $primaryKey = 'id_categoria';
   protected $fillable = [
      'id_categoria',
      'id_tipo_inventario',
      'glosa_categoria',
      'id_categoria_padre',
      'orden_categoria',
      'descripcion_categoria',
      'vigente_categoria',
      'pathimagen_categoria',
      'visibleonline_categoria',
      'urlamigable_categoria',
      'codigo_categoria'
   ];
}
