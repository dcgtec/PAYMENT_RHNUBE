<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentUsuario extends Model
{
    protected $table = 'detalle_payment_compra';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['id', 'codigo_compra', 'code_stripe', 'fecha_compra', 'dato_usuario', 'correo', 'estado', 'estado_transacion', 'comentario'];
}
