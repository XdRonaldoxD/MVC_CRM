<?php

use Illuminate\Database\Eloquent\Model;

class DetalleZonaOferta extends Model
{
   protected $table = 'detalle_zona_oferta';
   public $timestamps = false;
   protected $primaryKey = 'id_detalle_zona_oferta';
   protected $fillable = [
      'id_detalle_zona_oferta',
      'id_zona_oferta',
      'id_producto',
      'id_lista_precio',
      'preciolista_detalle_zona_oferta',
      'porcentajedescuento_detalle_zona_oferta',
      'preciototalventa_detalle_zona_oferta',
      'orden_detalle_zona_oferta'
   ];
}
