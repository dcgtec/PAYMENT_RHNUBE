<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvitacionInfluencer extends Model
{

    protected $table = 'invitacion_influencer';

    // Clave primaria
    protected $primaryKey = 'id_invitacion_influencer';

    // Permitir asignación masiva de estos campos
    protected $fillable = [
        'nombre_invitacion',
        'fecha_inicio',
        'fecha_fin',
        'cantidad_total',
        'cantidad_acumulada',
        'token',
        'link',
        'estado',
        'fecha_inicio', 'fecha_fin'
    ];

    public $timestamps = false;
}
