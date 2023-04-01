<?php

use Illuminate\Database\Eloquent\Model;

class EmpresaVentaOnline extends Model
{
    protected $table = 'empresa_venta_online';
    public $timestamps = false;
    protected $primaryKey = 'id_empresa_venta_online';
    protected $fillable = [
        'id_empresa_venta_online',
        'id_lista_precio',
        'id_sucursal',
        'id_bodega',
        'idDistrito',
        'ruc_empresa_venta_online',
        'nombre_empresa_venta_online',
        'razon_social_empresa_venta_online',
        'telefono_empresa_venta_online',
        'celular_empresa_venta_online',
        'direccion_empresa_venta_online',
        'giro_empresa_venta_online',
        'tokenaccesoapi_empresa_venta_online',
        'mostrarstockdisponibledesde_empresa_venta_online',
        'dominio_empresa_venta_online',
        'pathfoto_empresa_venta_online',
        'pixelgoogle_empresa_venta_online',
        'pixelfacebook_empresa_venta_online',
        'urlicono_empresa_venta_online',
        'public_idicono_empresa_venta_online',
        'urllogohorizontal_empresa_venta_online',
        'public_idlogohorizontal_empresa_venta_online',
        'urllogovertical_empresa_venta_online',
        'public_idlogovertical_empresa_venta_online',


    ];
}
