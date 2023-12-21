<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{

    protected $table = 'plans';

    protected $fillable = [
        'id',
        'id_plan',
        'name',
        'price',
        'stripe_plan',
        'tax'
    ];
}
