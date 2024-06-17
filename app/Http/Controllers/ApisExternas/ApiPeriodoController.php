<?php

namespace App\Http\Controllers\ApisExternas;

use App\Http\Controllers\Controller;
use App\periodo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiPeriodoController extends Controller
{
     function listPeriodo()
    {
        try {
            $periodos = periodo::select()->get();
            return response()->json($periodos);
        } catch (Exception $e) {
            Log::error('Error en la función listCupones: ' . $e->getMessage());
            return response()->json(['error' => 'Error listPeriodo.'], 500);
        }
    }
}
