<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class periodo extends Model
{
    //
    protected $table = 'tipo_periodo';
    protected $primaryKey = 'id_tipo_periodo';
    protected $fillable = ['id_tipo_periodo','periodo','slug'];
}
