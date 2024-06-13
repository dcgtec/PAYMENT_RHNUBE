<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class propietarios extends Model
{
    //
    protected $table = 'propietarios';
    protected $primaryKey = 'id_propietario';
    public $timestamps = false;
    protected $fillable = [
        'id_propietario', 'codigo', 'nombres', 'apellido_paterno', 'apellido_materno', 'razon_social', 'correo', 'numero_movil', 'usuario',
        'password', 'cargo', 'redes_sociales', 'foto_perfil', 'foto_portada', 'banco', 'tipo_de_cuenta', 'cci', 'numero_de_cuenta'
    ];
}
