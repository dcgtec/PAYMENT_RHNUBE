<?php

namespace App\Http\Middleware;

use Closure;

class NoCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Agregar encabezados de no-caché a la respuesta
        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT'); // Fecha en el pasado

        return $response;
    }
}
