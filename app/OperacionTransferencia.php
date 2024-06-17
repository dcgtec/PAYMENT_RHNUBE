<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperacionTransferencia extends Model
{
    protected $table = 'operaciones_transferencias';
    protected $primaryKey = 'id_operacion';
    public $timestamps = false;

    protected $fillable = [
        'id_detalle_payment_compra',
        'id_propietario',
        'id_compras',
        'cuentasBancarias',
        'numero_operacion',
        'fecha',
        'hora',
    ];
}
