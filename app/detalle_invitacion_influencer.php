<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class detalle_invitacion_influencer extends Model
{
    //
    protected $table = 'detalle_invitacion_influencer';
    protected $primaryKey = 'id_detalle_invitacion_influencer';
    protected $fillable = [
        'id_detalle_invitacion_influencer',
        'id_invitacion_influencer',
        'id_cupon',
        'fecha_creacion',
    ];
    public $timestamps = false;
}
