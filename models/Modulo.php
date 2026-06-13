<?php

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulo';
    public $timestamps = false;
    protected $primaryKey = 'id_modulo';
    protected $guarded = [];
}
