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
        return view('welcome', compact('categoryPlans'));
    }

    public function show($slug, $id)
    {
        // if (!auth()->check()) {
        //     // Usuario no autenticado, redirigir a la página de inicio de sesión
        //     return redirect('/login');
        // }

        // $user = auth()->user();
        // $intent = $user->createSetupIntent();

        // Obtener el CategoryPlan por el slug y el id
        $categoryPlan = CategoryPlan::where('slug', $slug)->where('id', $id)->firstOrFail();

        // Obtener los planes donde id_plan sea igual al parámetro proporcionado
        $plans = Plan::where('id_plan', $categoryPlan->id)->get();
        return view('payment', compact('categoryPlan','plans'));

    }
}
