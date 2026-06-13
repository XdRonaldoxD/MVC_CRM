<?php

use Illuminate\Database\Eloquent\Model;

class PerfilModulo extends Model
{
    protected $table = 'perfil_modulo';
    public $timestamps = false;
    protected $primaryKey = 'id_perfil_modulo';
    protected $guarded = [];
}
