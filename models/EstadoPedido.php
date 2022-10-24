<?php

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model
{
   protected $table = 'estado_pedido';
   public $timestamps = false;
   protected $primaryKey = 'id_estado_pedido';
   protected $fillable = [
      'id_estado_pedido',
      'glosa_estado_pedido',
      'color_estado_pedido',
      'orden_estado_pedido',
      'vigente_estado_pedido'

   ];
}
