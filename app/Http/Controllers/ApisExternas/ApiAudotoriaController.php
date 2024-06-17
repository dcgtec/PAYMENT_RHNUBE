<?php

namespace App\Http\Controllers\ApisExternas;

use App\AuditLog;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiAudotoriaController extends Controller
{

    function listAudotira()
    {
        try {
            $Audotira = AuditLog::all();
            return response()->json($Audotira);
        } catch (Exception $e) {
            Log::error('Error en la función listAudotira: ' . $e->getMessage());
            return response()->json(['error' => 'Error listAudotira.'], 500);
        }
    }

    function registerAudotira(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'table_name' => 'required|string|max:255',
            'operation' => 'required|in:INSERT,UPDATE,DELETE',
            'primary_key_value' => 'required|max:255',
            'changed_data' => 'required|json',
            'changed_by' => 'required|max:255',
        ]);


        // Crear un nuevo registro en la tabla de auditoría
        $auditLog = AuditLog::create([
            'table_name' => $request->table_name,
            'operation' => $request->operation,
            'primary_key_value' => $request->primary_key_value,
            'changed_data' => $request->changed_data,
            'changed_by' => $request->changed_by,
            'changed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Registro de auditoría creado con éxito',
            'data' => $auditLog
        ], 201);
    }
}
