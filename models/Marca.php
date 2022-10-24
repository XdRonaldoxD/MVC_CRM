<?php

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
   protected $table = 'marca';
   public $timestamps = false;
   protected $primaryKey = 'id_marca';
   protected $fillable = [
      'id_marca',
      'glosa_marca',
      'vigente_marca'
   ];
}
