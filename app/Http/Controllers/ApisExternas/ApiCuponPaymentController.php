<?php

namespace App\Http\Controllers\ApisExternas;

use App\cupon;
use App\detalle_cupones;
use App\Http\Controllers\Controller;
use App\Mail\CuponAsignado;
use App\paquete;
use App\propietarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Coupon;

class ApiCuponPaymentController extends Controller
{
    function listCupones()
    {
        try {
            $paymentUsuarios = cupon::select("id_cupon", "codigo_cupon", "name_cupon", "accion")->orderBy('id_cupon', 'desc')->get();
            return response()->json($paymentUsuarios);
        } catch (Exception $e) {
            Log::error('Error en la función listCupones: ' . $e->getMessage());
            return response()->json(['error' => 'Error listCupones.'], 500);
        }
    }

    function listCupon()
    {
        try {
            $cupones = cupon::with(['detallesCupones.paquete', 'detallesCupones.periodo'])->orderBy('id_cupon', 'desc')->get();

            // Filtrar combinaciones únicas de paquete y período para cada fila de cupón
            $cupones->transform(function ($cupon) {
                $paquetes = [];
                $periodos = [];

                foreach ($cupon->detallesCupones as $detalle) {
                    $paquetes[$detalle->paquete->id_paquete] = $detalle->paquete;
                    $periodos[$detalle->periodo->id_tipo_periodo] = $detalle->periodo;
                }

                // Reemplazar la clave detalles_cupones con un arreglo vacío
                unset($cupon->detallesCupones);
                $cupon->paquetes = array_values($paquetes);
                $cupon->periodos = array_values($periodos);
                return $cupon;
            });

            return response()->json($cupones);
        } catch (Exception $e) {
            Log::error('Error en la función listCupones: ' . $e->getMessage());
            return response()->json(['error' => 'Error listCupon.'], 500);
        }
    }

    function deleteCupon(Request $request)
    {
        try {

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $request->validate([
                'idCupon' => 'required'
            ]);

            $idCupon = $request->input('idCupon');


            $registro =  cupon::where('codigo_cupon', $idCupon)->first();

            // Obtener el cupón de Stripe
            $cupon = Coupon::retrieve($idCupon);

            if ($cupon->delete()) {
                $registro->accion = 0;
                $registro->save();
                return response()->json(['success' => true, 'message' => 'Cupón actualizado correctamente']);
            } else {
                return response()->json(['success' => false, 'message' => 'Cupón no actualizado']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Capturar cualquier error y devolver una respuesta de error
            return response()->json(['success' => false, 'message' => 'Cupón no encontrado']);
        }
    }

    function actCupon(Request $request)
    {
        try {

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $request->validate([
                'idCupon' => 'required',
                'desc' => 'required',
                'nombreCupon' => 'required',
                'cantUso' => 'required',
                'dateFin' => 'required'
            ]);


            $desc = $request->input('desc');
            $nombreCupon = $request->input('nombreCupon');
            $cantUso = $request->input('cantUso');
            $dateFin = $request->input('dateFin');
            $redeem_by_timestamp = strtotime($dateFin . ' 23:59:59');
            $idCupon = $request->input('idCupon');

            $registro =  cupon::where('codigo_cupon', $idCupon)->first();

            $stripeCoupon = \Stripe\Coupon::create([
                'percent_off' => $desc,
                'name' => $nombreCupon,
                'max_redemptions' => $cantUso,
                'redeem_by' => $redeem_by_timestamp,
            ]);

            $registro->accion = 1;
            $registro->codigo_cupon = $stripeCoupon->id;
            $registro->save();
            return response()->json(['success' => true, 'message' => 'Cupón actualizado correctamente']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Capturar cualquier error y devolver una respuesta de error
            return response()->json(['success' => false, 'message' => 'Cupón no encontrado']);
        }
    }

    function emailCupon(Request $request)
    {
        try {

            $request->validate([
                'correo' => 'required|email',
                'nombre' => 'required',
                'cupon' => 'required',
                'porcentaje' => 'required',
                'link' => 'required'
            ]);


            // Extraer el link del array de datos
            $email = $request->correo;
            $nombres = $request->nombre;
            $cupon = $request->cupon;
            $porcentaje = $request->porcentaje;
            $link = $request->link;


            Mail::to($email)->send(new CuponAsignado($nombres, $cupon, $porcentaje, $link));

            return response()->json(['success' => true, 'message' => 'Correo enviado exitosamente'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Funcion: emailCupon - Datos inválidos.',
                'errors' => $e->errors(), // Esto te dirá qué campos fallaron
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Funcion: emailCupon - ' . $e->getMessage()], 500);
        }
    }

    function accSaUpCupon(Request $request)
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $request->validate([
                'nombreCupon' => 'required',
                'accion' => 'required',
                'cantUso' => 'required',
                'dateInicio' => 'required',
                'dateFin' => 'required',
                'desc' => 'required',
                'propietario' => 'required',
                'paquete.*' => 'required',
                'periodo.*' => 'required',
                'ganancia' => 'required'
            ]);

            $nombreCupon = $request->input('nombreCupon');
            $cantUso = $request->input('cantUso');
            $dateInicio = $request->input('dateInicio');
            $dateFin = $request->input('dateFin');
            $redeem_by_timestamp = strtotime($dateFin . ' 23:59:59');
            $desc = $request->input('desc');
            $accion = $request->input('accion');
            $paquetes = $request->input('paquete');
            $periodos = $request->input('periodo');

            $ganancia = $request->input('ganancia');
            $id_propietario  = $request->input('propietario');

            if ($accion == "guardar") {
                if (cupon::where('name_cupon', $nombreCupon)->exists()) {
                    return response()->json(['success' => false, 'message' => 'El nombre del cupón ya está en uso']);
                }

                $stripeCoupon = \Stripe\Coupon::create([
                    'percent_off' => $desc,
                    'name' => $nombreCupon,
                    'max_redemptions' => $cantUso,
                    'redeem_by' => $redeem_by_timestamp,
                ]);

                $linkCupon = 'https://payment.rhnube.com.pe/payment?cupon=' . $nombreCupon;

                $nuevoCupon = new Cupon();
                $nuevoCupon->name_cupon = $nombreCupon;
                $nuevoCupon->codigo_cupon = $stripeCoupon->id;
                $nuevoCupon->cantidad_uso = $cantUso;
                $nuevoCupon->fecha_inicio = $dateInicio;
                $nuevoCupon->fecha_fin = $dateFin;
                $nuevoCupon->descuento = $desc;
                $nuevoCupon->ganancia = $ganancia;
                $nuevoCupon->id_propietario  = $id_propietario;
                $nuevoCupon->link = $linkCupon;
                $nuevoCupon->save();

                if (cupon::where('name_cupon', $nombreCupon)->exists()) {
                    $idCupon = cupon::where('name_cupon', $nombreCupon)->pluck('id_cupon')->first();
                    foreach ($paquetes as $paquete) {

                        foreach ($periodos as $periodo) {
                            $detalleCuponUso = new detalle_cupones();
                            $detalleCuponUso->id_cupon = $idCupon;
                            $detalleCuponUso->id_paquete = $paquete;
                            $detalleCuponUso->id_tipo_periodo = $periodo;
                            $detalleCuponUso->save();
                        }
                    }
                }

                $propietario = propietarios::where('id_propietario', $id_propietario)
                    ->select('nombres', 'correo')
                    ->first();

                $nombres = $propietario->nombres;
                $correo = $propietario->correo;

                $cuponRequest = new Request([
                    'correo' => $correo,
                    'nombre' =>  $nombres,
                    'cupon' =>  $nombreCupon,
                    'porcentaje' => $desc,
                    'link' => $linkCupon
                ]);

                $this->emailCupon($cuponRequest);

                return response()->json(['success' => true, 'message' => 'Cupón guardado exitosamente']);
            }
            if ($accion == "editar") {
                $idCupon = $request->input('idCupon');
                $cupon = cupon::where('codigo_cupon', $idCupon)->firstOrFail();

                $cupon->name_cupon = $nombreCupon;
                $cupon->ganancia = $ganancia;
                $cupon->id_propietario  = $id_propietario;
                $cupon->link = 'https://payment.rhnube.com.pe/payment?cupon=' . $nombreCupon;
                $idCuponbd = $cupon->id_cupon;
                $cuponStripe = \Stripe\Coupon::retrieve($idCupon);
                $cuponStripe->name = $nombreCupon;

                detalle_cupones::where('id_cupon', $idCuponbd)->delete();

                foreach ($paquetes as $paquete) {
                    $slugPaquete = paquete::where('id_paquete', $paquete)->pluck('slug')->first();
                    foreach ($periodos as $periodo) {
                        $detalleCuponUso = new detalle_cupones();
                        $detalleCuponUso->id_cupon = $idCuponbd;
                        $detalleCuponUso->id_paquete = $paquete;
                        $detalleCuponUso->id_tipo_periodo = $periodo;


                        $detalleCuponUso->save();
                    }
                }

                if ($cupon->save() && $cuponStripe->save()) {
                    return response()->json(['success' => true, 'message' => 'Cupon editado exitosamente']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Error al guardar el cupon'], 500);
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Capturar cualquier error y devolver una respuesta de error
            Log::error('Error en la función accSaUpCupon: ' . $e->getMessage());
            return response()->json(['error' => 'Error accSaUpCupon.'], 500);
        }
    }
}
