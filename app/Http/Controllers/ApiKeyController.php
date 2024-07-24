<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function generateKey()
    {
        // Definir un tiempo de expiración aleatorio entre 30 minutos y 24 horas
        $expiresAt = Carbon::now()->addMinutes(rand(30, 1440));
        $key = Str::random(32);

        DB::table('api_keys')->insert([
            'key' => $key,
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'key' => $key,
            'expires_at' => $expiresAt->toDateTimeString(),
        ]);
    }
}
