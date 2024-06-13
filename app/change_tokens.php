<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class change_tokens extends Model
{

    protected $table = 'change_tokens';
    public $timestamps = false;

    protected $fillable = [
        'id_propietario',
        'token',
        'type_token',
        'expires_at',
        'codigo_validacion'
    ];

    public function propietario()
    {
        return $this->belongsTo(propietarios::class, 'id_propietario');
    }
}
