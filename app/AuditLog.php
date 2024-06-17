<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{

    protected $table = 'audit_log';

    // Definir los campos que pueden ser rellenados
    protected $fillable = [
        'table_name', 'operation', 'primary_key_value', 'changed_data', 'changed_by', 'changed_at'
    ];

    // Desactivar las marcas de tiempo automáticas de Laravel, ya que estamos manejando 'changed_at' manualmente
    public $timestamps = false;
}
