<?php
use Illuminate\Database\Eloquent\Model;
class Sucursal extends Model
{
    protected $table="sucursal";
    protected $fillable = [
        'id_sucursal',
        'idDistrito',
        'codigo_sucursal',
        'glosa_sucursal',
        'encargado_sucursal',
        'direccion_sucursal',
        'telefono_sucursal',
        'e_mail_sucursal',
        'mapa_sucursal',
        'descripcion_sucursal',
        'horario_sucursal',
        'vigente_sucursal',
        'buscacodigobarra_sucursal',
        'idclientedefectopos_sucursal',
        'mediopagodefectopos_sucursal',
        'idusuarioventaonlinedefecto_sucursal',
        'idbodegaonlinedefecto_sucursal'
    ];

    protected $primaryKey = 'id_sucursal';
    public $timestamps = false;
}
