<?php

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincia';
    public $timestamps = false;
    protected $primaryKey = 'idProvincia';
    protected $fillable = [
        'idProvincia',
        'provincia',
        'idDepartamento'
    ];
}
