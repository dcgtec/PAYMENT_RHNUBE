<?php

namespace App\Http\Controllers\ApisExternas;

use App\Http\Controllers\Controller;
use App\paquete;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiPaqueteController extends Controller
{
     function listPaquete()
    {
        try {
            $paquetes = paquete::select()->get();
            return response()->json($paquetes);
        } catch (Exception $e) {
            Log::error('Error en la función listCupones: ' . $e->getMessage());
            return response()->json(['error' => 'Error listPeriodo.'], 500);
        }
    }
}
