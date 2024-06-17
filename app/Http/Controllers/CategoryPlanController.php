<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoryPlan;
use App\cupon;
use App\Plan;
use Illuminate\Support\Facades\Http;

class CategoryPlanController extends Controller
{
    public function index()
    {
        //$categoryPlans = CategoryPlan::get();
        // return view('welcome', compact('categoryPlans'));
        return redirect('https://www.rhnube.com/precios');
    }

    public function show(Request $request, $slug = null)
    {
        try {
            // Validate 'periodo' parameter if present
            if ($request->has('periodo')) {
                $request->validate([
                    'periodo' => 'required|numeric|between:1,4',
                ]);
            }

            $codigo = $request->input('cupon');

            if (!$codigo) {
                return view('paymentCupon', ['datos' => null]);
            }

            $cupon = cupon::where('name_cupon', $codigo)->first();

            if (!$cupon) {
                return view('paymentCupon', ['datos' => null]);
            }

            $fechaActual = now();
            $fechaFinCupon = \Carbon\Carbon::parse($cupon->fecha_fin);

            if ($fechaFinCupon->isPast() && !$fechaFinCupon->isSameDay($fechaActual)) {
                return view('paymentCupon', ['datos' => null]);
            }

            if ($cupon->cant_usada >= $cupon->cantidad_uso) {
                return view('paymentCupon', ['datos' => null]);
            }

            $cupones = cupon::with(['detallesCupones.paquete', 'detallesCupones.periodo'])
                ->where('accion', '1')
                ->where('name_cupon', $codigo)
                ->get();

            $cupones->transform(function ($cupon) {
                $paquetes = [];
                $periodos = [];

                foreach ($cupon->detallesCupones as $detalle) {
                    $paquetes[$detalle->paquete->id_paquete] = $detalle->paquete;
                    $periodos[$detalle->periodo->id_tipo_periodo] = $detalle->periodo;
                }

                unset($cupon->detallesCupones, $cupon->password, $cupon->usuario);

                $cupon->paquetes = array_values($paquetes);
                $cupon->periodos = array_values($periodos);
                return $cupon;
            });

            $datos = $cupones->first();
            return view('paymentCupon', ['datos' => $datos ? $datos->toArray() : null]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exception
            return abort(404);
        } catch (\Exception $e) {
            // Handle other exceptions
            return abort(404);
        }
    }
}
