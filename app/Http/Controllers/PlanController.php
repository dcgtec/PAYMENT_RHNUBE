<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use Illuminate\Support\Facedes\Auth;
use App\User;

class PlanController extends Controller
{

    // CategoryPlanController.php
    public function updatePrices(Request $request)
    {


        // Obtener el ID del plan seleccionado y la cantidad de empleados desde la solicitud
        $planId = $request->input('plan_id');
        $quantity = $request->input('quantity');
        $country = $request->input('country');
        $descuento = number_format($request->input('descuento'), 2, '.', '');

        $cadDescu =  number_format($descuento, 2, '.', '') . '%';

        // Obtener la información del nuevo plan
        $newPlan = Plan::findOrFail($planId);

        $numPlan = $newPlan->totNumMonth;
        $price = $newPlan->price;
        $subtotal = number_format($price * $quantity * $numPlan, 2, '.', '');
        // $newtax = number_format($subtotal * $newPlan->tax / 100, 2, '.', '');

        $subDescu = number_format($subtotal * (1 - ($descuento / 100)), 2, '.', '');
        

        if ($country == 'PE') {
            $newtax = number_format(0.18, 2, '.', '');
        } else {
            $newtax = number_format(0.00, 2, '.', '');
        }

        $impuesto = number_format($subDescu * $newtax, 2, '.', '');


        $total = number_format($subDescu  + $impuesto, 2, '.', '');
        // Devolver la información actualizada
        return response()->json([
            'descuento' => $cadDescu,
            'price' => $price,
            'subtotal' => $subtotal,
            'subDes' => $subDescu,
            'impuesto' => $impuesto,
            'total' => $total
        ]);
    }
}
