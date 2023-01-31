<?php
use Illuminate\Database\Eloquent\Model;
class SuccessMercadoPago extends Model
{
    protected $table = "success_mercadopago";
    protected $primaryKey = 'id_success_mercadopago';

    protected $fillable = [
        'id_success_mercadopago',
        'id_notificacion_mercadopago',
        'collection_id_success_mercadopago',
        'collection_status_success_mercadopago',
        'payment_id_success_mercadopago',
        'status_success_mercadopago',
        'external_reference_success_mercadopago',
        'payment_type_success_mercadopago',
        'merchant_order_id_success_mercadopago',
        'preference_id_success_mercadopago',
        'site_id_success_mercadopago',
        'processing_mode_success_mercadopago',
        'merchant_account_id_success_mercadopago',
        'fecha_creacion_success_mercadopago'
    ];

    public $timestamps = false;
}
