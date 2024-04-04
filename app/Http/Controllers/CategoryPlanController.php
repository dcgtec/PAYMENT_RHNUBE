<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryPlan;
use App\Plan;
use Illuminate\Support\Facades\Http;

class CategoryPlanController extends Controller
{
    public function index()
    {
        $categoryPlans = CategoryPlan::get();
        // return view('welcome', compact('categoryPlans'));
        return redirect('https://www.rhnube.com/precios');
    }

    public function show(Request $request, $slug = null)
    {
        try {
            // Validar el parámetro 'periodo' si está presente
            if ($request->has('periodo')) {
                $request->validate([
                    'periodo' => 'required|numeric|between:1,4',
                ]);

                $periodo = $request->input('periodo');

                // Verificar si el período está en el rango permitido
                if ($periodo < 1 || $periodo > 4) {
                    abort(404);
                }
            }


            if (!$slug) {

                $cupon = $request->input('cupon');



                $params = [
                    'codigo' => $cupon,
                    'paquete' => '0',
                ];

                $response = Http::post('https://beta.rhnube.com.pe/api/updateUseCupon', $params);

                if ($response->successful()) {
                    // Obtener los datos de la respuesta
                    $responseData = $response->json();
                    $datos =  $responseData["message"][0];
                    return view('paymentCupon', compact('datos'));
                } else {
                    // En caso de error en la solicitud, devolver un mensaje de error
                    return response()->json(['error' => 'Error al consumir la API'], $response->status());
                }
            } else {
                // Realizar acciones cuando hay un slug proporcionado
                $categoryPlan = CategoryPlan::where('slug', $slug)->firstOrFail();

                // Verificar si el recurso no se encuentra
                if (!$categoryPlan) {
                    abort(404);
                }

                $periodoPago = Plan::where('id_plan', $categoryPlan->id)->get();

                // Si se proporciona un período, filtra los planes según el período
                if ($request->has('periodo')) {
                    $plans = Plan::where('id_plan', $categoryPlan->id)->where('n_periodo', $periodo)->get();
                } else {
                    // Si no se proporciona un período, obtén todos los planes asociados con el plan de categoría
                    $plans = Plan::where('id_plan', $categoryPlan->id)->get();
                }

                // Verificar si no hay planes encontrados
                if ($plans->isEmpty()) {
                    abort(404);
                }

                return view('payment', compact('categoryPlan', 'plans', 'periodo', 'periodoPago'));
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Manejar la excepción de validación
            abort(404);
        } catch (\Exception $e) {
            abort(404);
        }
    }
}