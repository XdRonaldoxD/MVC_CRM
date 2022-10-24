<?php

use Illuminate\Database\Eloquent\Model;

class EstadoPreparacion extends Model
{
   protected $table = 'estado_preparacion';
   public $timestamps = false;
   protected $primaryKey = 'id_estado_preparacion';
   protected $fillable = [
      'id_estado_preparacion',
      'glosa_estado_preparacion',
      'color_estado_preparacion',
      'orden_estado_preparacion',
      'vigente_estado_preparacion'
   ];
}
