<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detalle_cupones extends Model
{
    //
    protected $table = 'detalle_cupon';
    protected $primaryKey = 'id_detalle_cupon_uso';
    protected $fillable = [
        'id_detalle_cupon_uso',
        'id_cupon',
        'id_paquete',
        'id_tipo_periodo',
        'fecha_inicio',
        'ganancia',
        'link'
    ];
    public $timestamps = false;
}
