<?php

use Illuminate\Database\Eloquent\Model;

class EstadoPago extends Model
{
   protected $table = 'estado_pago';
   public $timestamps = false;
   protected $primaryKey = 'id_estado_pago';
   protected $fillable = [
      'id_estado_pago',
      'glosa_estado_pago',
      'color_estado_pago',
      'orden_estado_pago',
      'vigente_estado_pago'
   ];
}
