<?php

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'distrito';
    public $timestamps = false;
    protected $primaryKey = 'idDistrito';
    protected $fillable = [
        'idDistrito',
        'distrito',
        'idProvincia'
    ];
}
