<?php
use Illuminate\Database\Eloquent\Model;
class Unidad extends Model
{
    protected $table = "unidad";
    protected $primaryKey = 'id_unidad';
    protected $fillable = [
        'id_unidad',
        'glosa_unidad',
        'order_unidad',
        'vigente_unidad'
    ];
    public $timestamps = false;
}
