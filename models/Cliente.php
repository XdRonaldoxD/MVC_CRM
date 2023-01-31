<?php

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
   protected $table = 'cliente';
   public $timestamps = false;
   protected $primaryKey = 'id_cliente';
   protected $fillable = [
    'id_cliente',
    'id_institucion_financiera',
    'id_tipo_cuenta',
    'id_comuna',
    'idProvincia',
    'idDistrito',
    'dni_cliente',
    'dv_cliente',
    'tipodocumento_cliente',
    'nombre_cliente',
    'apellidopaterno_cliente',
    'apellidomaterno_cliente',
    'e_mail_cliente',
    'telefono_cliente',
    'celular_cliente',
    'direccion_cliente',
    'numerocuenta_cliente',
    'comentario_cliente',
    'rutcuenta_cliente',
    'nombrecuenta_cliente',
    'emailcuenta_cliente',
    'giro_cliente',
    'fechacreacion_cliente',
    'fechanacimiento_cliente',
    'mediollegada_cliente',
    'vigente_cliente',
    'esmigrado_cliente',
    'contraseniaactualizada_cliente',
    'ruc_cliente'
   ];
}
