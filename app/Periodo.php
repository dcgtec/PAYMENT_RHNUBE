<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{

    protected $table = 'tipo_periodo';

    protected $fillable = [
        'id_tipo_periodo ',
        'periodo',
        'slug'
    ];
}
