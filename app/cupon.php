<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cupon extends Model
{
    //
    protected $table = 'cupon_payment';
    protected $primaryKey = 'id_cupon';
    public $timestamps = false;
    protected $fillable = [
        'id_cupon',
        'codigo_cupon',
        'name_cupon',
        'cantidad_uso',
        'cant_usada',
        'fecha_inicio',
        'fecha_fin',
        'id_propietario',
        'descuento',
        'ganancia',
        'link',
        'accion'
    ];

    public function detallesCupones()
    {
        return $this->hasMany(detalle_cupones::class, 'id_cupon');
    }

    public function propietario()
    {
        return $this->belongsTo(propietarios::class, 'id_propietario', 'id_propietario');
    }
}
