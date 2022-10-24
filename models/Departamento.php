<?php

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    public $timestamps = false;
    protected $primaryKey = 'idDepartamento';
    protected $fillable = [
        'idDepartamento',
        'departamento',
        'idPais'
    ];
}
