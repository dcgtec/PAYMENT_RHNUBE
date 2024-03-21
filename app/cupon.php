<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cupon extends Model
{
    //
    protected $table = 'cupon_payment';
    protected $primaryKey = 'id_cupon';
    protected $fillable = ['id_cupon', 'codigo_cupon', 'name_cupon', 'cantidad_uso', 'fecha_inicio', 'fecha_fin', 'descuento'];
}
