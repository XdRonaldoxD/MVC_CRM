<?php
use Illuminate\Database\Eloquent\Model;
class Staff extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'id_staff';
    public $timestamps = false;
    protected $fillable = [
        'id_staff',
        'id_tipo_agenda',
        'rut_staff',
        'dv_staff',
        'nombre_staff',
        'apellidopaterno_staff',
        'apellidomaterno_staff',
        'e_mail_staff',
        'telefono_staff',
        'celular_staff',
        'img_staff',
        'vigente_staff',
        'aplicacomision_staff',
        'reservaonline_staff',
        'valorcomision_staff',
        'sexo_staff',
        'fechanacimiento_staff',
        'gestionarhorario_staff',
        'visibleportal_staff',
        'descripcion_staff',
        'porcentajeurgencia_staff',
        'porcentajeprocedimiento_staff',
        'porcentajecirugia_staff',
        'internoexterno_staff',
        'tituloweb_staff',
        'pathimgen_staff',
        'vigente_staff',
        'porcentajecirugia_staff',
        'segmento_staff',
        'detalleportal_staff',
        'veratenciones_staff',
        'verregistroshospital_staff',
        'maximodiasparareservar_staff',
        'recibirresumenreserva_staff',
        'pathfirma_staff',
        'profesioncargo_staff'
    ];
}
