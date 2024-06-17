<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paquete extends Model
{
    //
    protected $table = 'paquete_payment';
    protected $primaryKey = 'id_paquete';
    protected $fillable = ['id_paquete','paquete','slug'];
}
