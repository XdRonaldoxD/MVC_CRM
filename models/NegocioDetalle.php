<?php

use Illuminate\Database\Eloquent\Model;
class NegocioDetalle extends Model
{
    protected $table = "negocio_detalle";
    protected $primaryKey = 'id_negocio_detalle';
    protected $fillable = [
        'id_negocio_detalle',
        'id_producto',
        'id_hospital',
        'id_negocio',
        'id_descuento',
        'id_lista_precio',
        'id_tipo_afectacion',
        'id_presupuesto_asignado',
        'valorneto_negocio_detalle',
        'descuento_negocio_detalle',
        'valordescuento_negocio_detalle',
        'descuentogeneral_negocio_detalle',
        'porcentajedescuentogeneral_negocio_detalle',
        'valorrecargo_negocio_detalle',
        'porcentajerecargo_negocio_detalle',
        'valorrecargogeneral_negocio_detalle',
        'iva_negocio_detalle',
        'total_negocio_detalle',
        'fechacreacion_negocio_detalle',
        'cantidad_negocio_detalle',
        'valorafecto_negocio_detalle',
        'valorexento_negocio_detalle',
        'preciounitario_negocio_detalle',
        'orden_negocio_detalle',
        'horasdiashospital_negocio_detalle',
        'tipohoradiashospital_negocio_detalle',
        'costohoradiashospital_negocio_detalle',
        'eshotel_hospital_negocio_detalle',
        'asignadotratamiento_negocio_detalle',
        'comentariofacturacion_negocio_detalle',

    ];
    public $timestamps = false;
}
