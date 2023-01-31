<?php
use Illuminate\Database\Eloquent\Model;
class NotificacionMercadoPago extends Model
{
    protected $table = "notificacion_mercadopago";
    protected $primaryKey = 'id_notificacion_mercadopago';

    protected $fillable = [
        'id_notificacion_mercadopago',
        'data_created_id',
        'json_notificacion_mercadopago',
        'fecha_creacion_notificacion_mercadopago'
    ];

    public $timestamps = false;
}
