<?php

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfil';
    public $timestamps = false;
    protected $primaryKey = 'id_perfil';
    protected $fillable = [
        'id_perfil',
        'glosa_perfil',
        'vigente_perfil',
        'perfildefecto_cliente'
    ];
}
