<?php

namespace App\Http\Controllers\Automatizacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AutomatizacionController extends Controller
{
     public function registroEmpresa(Request $request){
        return view('Automatizacion.index');
    }
}
