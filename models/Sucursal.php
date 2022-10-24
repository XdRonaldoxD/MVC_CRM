<?php
use Illuminate\Database\Eloquent\Model;
class Sucursal extends Model
{
    protected $table="sucursal";
    protected $fillable = [
        'id_sucursal',
        'id_comuna',
        'codigo_sucursal',
        'glosa_sucursal',
        'encargado_sucursal',
        'direccion_sucursal',
        'telefono_sucursal',
        'e_mail_sucursal',
        'mapa_sucursal',
        'descripcion_sucursal',
        'horario_sucursal',
        'urgenciacostohabil_sucursal',
        'urgenciacostofestivo_sucrusal',
        'hospitaltipocobro_sucursal',
        'hospitalcobro_sucursal',
        'hoteltipocobro_sucursal',
        'hotelcobro_sucursal',
        'vigente_sucursal',
        'aplicaivaurgencia_sucursal',
        'aplicaivahospital_sucursal',
        'aplicaivahotel_sucursal',
        'buscacodigobarra_sucursal',
        'ventaproductosinstock_sucursal',
        'idclientedefectopos_sucursal',
        'identidaddefectopos_sucursal',
        'mediopagodefectopos_sucursal',
        'idusuarioventaonlinedefecto_sucursal',
        'idbodegadefecto_sucursal',
        'idbodegaonlinedefecto_sucursal'
    ];

    protected $primaryKey = 'id_sucursal';
    public $timestamps = false;
}
