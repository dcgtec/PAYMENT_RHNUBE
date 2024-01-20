<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryPlan;
use App\Plan;

class CategoryPlanController extends Controller
{
    public function index()
    {
        $categoryPlans = CategoryPlan::get();
        // return view('welcome', compact('categoryPlans'));
        return redirect('https://www.rhnube.com/precios');
    }

    public function show(Request $request, $slug)
    {
        try {
            // Validación del parámetro 'periodo'
            $request->validate([
                'periodo' => 'required|numeric|between:1,4',
            ]);

            $periodo = $request->input('periodo');

            // Verificar si el período está en el rango permitido
            if ($periodo < 1 || $periodo > 4) {
                abort(404);
            }

            $categoryPlan = CategoryPlan::where('slug', $slug)->firstOrFail();

            // Verificar si el recurso no se encuentra
            if (!$categoryPlan) {
                abort(404);
            }

            $periodoPago = Plan::where('id_plan', $categoryPlan->id)->get();

            $plans = Plan::where('id_plan', $categoryPlan->id)->where('n_periodo', $periodo)->get();

            // Verificar si no hay planes encontrados
            if ($plans->isEmpty()) {
                abort(404);
            }

            return view('payment', compact('categoryPlan', 'plans', 'periodo', 'periodoPago'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejar la excepción de validación
            abort(404);
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
