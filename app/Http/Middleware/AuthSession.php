<?php

namespace App\Http\Middleware;

use Closure;

class AuthSession
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
        // Si no está 'logeado', redirigir al login
        if (!session()->has('logeado')) {
            return redirect('/iniciarSesion'); // Ajusta la ruta del login según corresponda
        }

        return $next($request); // Si está 'logeado', continuar
    }
}
