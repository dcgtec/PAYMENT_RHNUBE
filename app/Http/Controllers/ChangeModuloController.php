<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChangeModuloController extends Controller
{

    public function showPassword(Request $request)
    {
        $ce = $request->query('ce');

        if (!$ce) {
            // Si 'cid' no está presente, devolver una respuesta de error o redirigir
            abort(404);
        }

        return view('influencers.changePassword');

        $response = Http::post('https://beta.rhnube.com.pe/api/validarToken', [
            'token' => $ce
        ]);


        // Si la API responde con 1, permitir el acceso
        if ($response->json() === 1) {
            $this->logout();
            session()->put('tokenRegistro', $ce);
            return view('influencers.registrosNuevos', compact('ce'));
        }

        abort(404);
    }
}
