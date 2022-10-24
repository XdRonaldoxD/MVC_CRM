<?php

use Illuminate\Database\Eloquent\Model;

class Atributo extends Model
{
   protected $table = 'atributo';
   public $timestamps = false;
   protected $primaryKey = 'id_atributo';
   protected $fillable = [
      'id_atributo',
      'glosa_atributo',
      'id_padre_atributo',
      'descripcion_atributo',
      'orden_atributo',
      'path_atributo',
      'vigente_atributo',
      'visibleimagenonline_atributo',
      'visiblemultiseleccion_atributo'
   ];
}
