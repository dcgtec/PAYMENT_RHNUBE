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
    ];
    public $timestamps = false;

    public function cupon()
    {
        return $this->belongsTo(cupon::class, 'id_cupon');
    }

    public function paquete()
    {
        return $this->belongsTo(paquete::class, 'id_paquete');
    }

    public function periodo()
    {
        return $this->belongsTo(periodo::class, 'id_tipo_periodo');
    }
}
