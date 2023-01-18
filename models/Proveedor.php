<?php
use Illuminate\Database\Eloquent\Model;
class Proveedor extends Model
{
    protected $table = "proveedor";
    protected $primaryKey = 'id_proveedor';
    protected $fillable = [
        'id_proveedor',
        'ruc_proveedor',
        'glosa_proveedor',
        'direccion_proveedor',
        'telefono_proveedor',
        'e_mail_proveedor',
        'comentario_proveedor',
        'vigente_proveedor'
    ];
    public $timestamps = false;
}
