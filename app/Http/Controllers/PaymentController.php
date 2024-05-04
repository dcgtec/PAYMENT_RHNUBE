<?php

namespace App\Http\Controllers;

use App\CategoryPlan;
use App\cupon;
use App\detalle_cupones;
use App\Mail\CompraExitosa;
use App\Periodo;
use Illuminate\Http\Request;
use App\Plan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Order;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

    public function obtenerPlan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plan' => 'required',
                'periodo' => 'required',
            ]);

            $idplan = $request->input('plan');
            $idPeriodo = $request->input('periodo');

            $periodo = Periodo::select('periodo')->where('id_tipo_periodo', $idPeriodo)->firstOrFail();
            $plan = Plan::select('id')->where('id_plan', $idplan)
                ->where('name', $periodo->periodo)
                ->firstOrFail();

            return response()->json($plan['id']); // Por ejemplo, podrías devolver el plan en formato JSON
        } catch (\InvalidArgumentException $e) {
            // Manejar errores de validación de entrada
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Manejar errores cuando no se encuentra el modelo
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Manejar errores de la API de Stripe
            return response()->json(['error' => 'Error en la comunicación con Stripe: ' . $e->getMessage()], 500);
        }
    }


    public function checkout(Request $request)
    {

        try {

            $quanty = $request->input('quanty');
            $idplan = $request->input('idPla');
            $country = $request->input('idCountry');




            // Validación de entrada
            if (!is_numeric($quanty) || !is_numeric($idplan) || !in_array($country, ['PE', 'otro_pais_valido'])) {
                throw new \InvalidArgumentException('Entrada inválida. Verifica los valores proporcionados.');
            }

            // Validación de existencia del plan
            $plan = Plan::find($idplan);

            if (!$plan) {
                throw new \Exception('El plan especificado no existe.');
            }

            $cupon = $request->input('cupon');

            $response = Http::post('https://beta.rhnube.com.pe/api/obtenerdatosCupon', [
                'codigo' => $cupon,
            ]);


            $data = $response->json();
            $successCu = $data["success"];

            $codCupn = '';
            $cant_usada = '';

            if ($successCu) {
                $codCupn = $data["message"]["codigo_cupon"];
                $cant_usada =  $data["message"]["cant_usada"];
            }

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Configuración de impuestos según el país
            $taxes = [];
            if ($country == "PE") {
                $taxes = ['txr_1OMyoaCbKz5YJFE3ajJh9S4U'];
            }

            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $plan->stripe_plan,
                    'quantity' => $quanty,
                    'tax_rates' => $taxes
                ]],
                'mode' => 'subscription',
                'success_url' => route('success'),
                'cancel_url' => route('index'),
            ];

            // Include discounts parameter only if $codCupn is not empty
            if (!empty($codCupn)) {
                $sessionData['discounts'] = [[
                    'coupon' => $codCupn,
                ]];
            }

            $session = \Stripe\Checkout\Session::create($sessionData);
            // Guardar la ID de la sesión de Stripe en la sesión de Laravel
            session([
                'stripe_session_id' => $session->id,
                'cantEmpleados' => $quanty,
                'plan' => $idplan,
                'cant_usada' => $cant_usada,
                'codCupn' => $cupon
            ]);

            // Redirigir al cliente a la página de checkout de Stripe
            return redirect()->away($session->url);
        } catch (\InvalidArgumentException $e) {
            session('message');
            // Manejar errores de validación de entrada
            return redirect()->route('error')->with('message', $e->getMessage());
        } catch (\Exception $e) {
            session('message');
            // Manejar otros errores
            return redirect()->route('error')->with('message', $e->getMessage());
        } catch (\Stripe\Exception\ApiErrorException $e) {
            session('message');
            // Manejar errores de la API de Stripe
            return redirect()->route('error')->with('message', 'Error en la comunicación con Stripe: ' . $e->getMessage());
        }
    }


    public function error()
    {
        $message = session('message'); // Obtener el mensaje de la sesión
        return view('error', ['message' => $message]);
    }


    public function reenviarCorreo(Request $request)
    {
        try {

            $correoElectronico = $request->input('correoElectronico');
            // Obtener el ID de la sesión de Stripe de la sesión
            $stripeSessionId = session('stripe_session_id');
            $plan = session('plan');
            $cantEmpleados = session('cantEmpleados');

            $plan = Plan::select('id_plan', 'name', 'totNumMonth')
                ->where('id', $plan)
                ->first();

            $category = CategoryPlan::select('slug')
                ->where('id', $plan->id_plan)
                ->first();

            // Establecer la clave secreta de Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $stripeSession = \Stripe\Checkout\Session::retrieve($stripeSessionId);
            $valorNumerico = abs(crc32($stripeSession->id));
            $codigoGenerado = str_pad($valorNumerico, 10, '0', STR_PAD_LEFT);

            // Obtener los valores
            $name = $plan->name;
            $cantEmpleados = $cantEmpleados; // Supongo que ya tienes este valor definido
            $categorySlug = $category->slug;
            $customerName = explode(' ', $stripeSession->customer_details->name)[0];
            $fecha = \Carbon\Carbon::parse($stripeSession->created)->format('Y-m-d H:i:s');
            $monto = $stripeSession->amount_total / 100;

            // Verificar si la dirección de correo electrónico es válida
            $correoDestino = filter_var($correoElectronico, FILTER_VALIDATE_EMAIL);

            if ($correoDestino) {
                Mail::to($correoDestino)->send(new CompraExitosa($codigoGenerado, $customerName, $fecha, $monto));
                return response()->json(['success' => true, 'message' => 'Correo electrónico reenviado exitosamente']);
            } else {
                throw new \Exception('La dirección de correo electrónico no es válida');
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al reenviar el correo electrónico: ' . $e->getMessage()]);
        }
    }


    public function success()
    {
        // Obtener el ID de la sesión de Stripe de la sesión
        $stripeSessionId = session('stripe_session_id');
        $plan = session('plan');
        $cantEmpleados = session('cantEmpleados');
        $cant_usada = session('cant_usada');
        $codCupn = session('codCupn');

        if (!$codCupn) {
            $codCupn = '';
        }


        $plan = Plan::select('id_plan', 'name', 'totNumMonth')
            ->where('id', $plan)
            ->first();

        $category = CategoryPlan::select()
            ->where('id', $plan->id_plan)
            ->first();



        // Establecer la clave secreta de Stripe
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));


        $stripeSession = \Stripe\Checkout\Session::retrieve($stripeSessionId);
        $valorNumerico = abs(crc32($stripeSession->id));
        $codigoGenerado = str_pad($valorNumerico, 10, '0', STR_PAD_LEFT);

        // Obtener los valores
        $name = $plan->name;
        $cantEmpleados = $cantEmpleados; // Supongo que ya tienes este valor definido
        $categorySlug = $category->slug;
        $customerName = $stripeSession->customer_details->name;
        $fecha = \Carbon\Carbon::parse($stripeSession->created)->format('Y-m-d H:i:s');
        $monto = $stripeSession->amount_total / 100;


        $ganancia = 0;
        $porGanancia = 0;
        $params = [
            'codigo' => $codCupn,
            'paquete' => '0',
        ];

        $respoCupon = Http::post('https://beta.rhnube.com.pe/api/updateUseCupon', $params);

        if ($respoCupon->successful()) {

            $responseData = $respoCupon->json();

            // Verificar si el mensaje está vacío
            if (!empty($responseData['message'])) {
                // Si hay datos en el mensaje, continuar con el procesamiento
                $datos = $responseData["message"][0];
                $porGanancia = round($datos["ganancia"], 2);
                $ganancia = round($monto * $porGanancia / 100, 2);
            }
        }


        $jsonCompra = [
            'codCupn' => $codCupn,
            'namePlan' => $name,
            'cantEmpleados' => $cantEmpleados,
            'categorySlug' => $categorySlug,
            'customerName' => $customerName,
            'total' => $monto,
            'porGanancia' => $porGanancia,
            'ganancia' => $ganancia
        ];

        $jsonDetalleCompra = json_encode($jsonCompra);


        // Consumir la API
        $response = Http::get('https://beta.rhnube.com.pe/api/verificarSaveCodigo', [
            'codigo_compra' => $codigoGenerado,
        ]);


        $data = $response->json();

        if (!$data['message']) {
            $newParams = [
                'codigo_compra' => $codigoGenerado,
                'dato_usuario' =>  $jsonDetalleCompra,
                'code_stripe' => $stripeSession->id,
                'correo' => $stripeSession->customer_details->email,
            ];

            $responseNew = Http::post('https://beta.rhnube.com.pe/api/saveCode', $newParams);
            $responseCu = $this->actualizarCodigo($codCupn);
            Mail::to($stripeSession->customer_details->email)->send(new CompraExitosa($codigoGenerado, $customerName, $fecha, $monto));
        }


        // Verificar el estado de la transacción de pago
        if ($stripeSession->mode === 'subscription' && $stripeSession->status === 'complete') {
            // El pago fue aprobado, puedes procesar la orden o mostrar una página de éxito
            return view('success', ['paymentIntent' => $stripeSession]);
        } else {
            // El pago no fue aprobado, puedes redirigir a una página de error o manejarlo según tus necesidades
            return view('error', ['message' => 'El pago no fue aprobado']);
        }
    }


    public function actualizarCodigo($cupon)
    {
        $valorCupon = $cupon;
        $cupon = cupon::where('name_cupon', $cupon)->first();

        if (!$cupon) {
            return response()->json(['success' => false, 'message' => 'Cupón no encontrado']);
        }




        $fechaActual = now(); // Obtener la fecha y hora actual
        $fechaFinCupon = \Carbon\Carbon::parse($cupon->fecha_fin); // Convertir la fecha de finalización del cupón a un objeto Carbon

        if ($fechaFinCupon->isPast() && !$fechaFinCupon->isSameDay($fechaActual)) {
            return response()->json(['success' => false, 'message' => 'El cupón ha caducado']);
        }




        if ($cupon->cant_usada < $cupon->cantidad_uso) {

            $cantiReem = $cupon->cant_usada += 1;
            $cuponUseUpdate = cupon::where('name_cupon', $valorCupon)->firstOrFail();
            $cuponUseUpdate->cant_usada = $cantiReem;
            $cuponUseUpdate->save();
            return response()->json(['success' => true, 'message' => 'Actualizado']);
        } else {
            return response()->json(['success' => false, 'message' => 'El cupón limitado'], 400);
        }
    }

    public function obtenerdatosCupon($cupon)
    {
        $cupon = Cupon::where('name_cupon', $cupon)->first();
        if (!$cupon) {
            return response()->json(['success' => false, 'message' => 'Cupón no encontrado']);
        }

        if (!$cupon) {
            return response()->json(['success' => false, 'message' => 'Cupón no encontrado']);
        }

        $fechaActual = now(); // Obtener la fecha y hora actual
        $fechaFinCupon = \Carbon\Carbon::parse($cupon->fecha_fin); // Convertir la fecha de finalización del cupón a un objeto Carbon

        if ($fechaFinCupon->isPast() && !$fechaFinCupon->isSameDay($fechaActual)) {
            return response()->json(['success' => false, 'message' => 'El cupón ha caducado']);
        }


        if ($cupon->cant_usada < $cupon->cantidad_uso) {
            $cantiReem = $cupon->cant_usada += 1;
            return response()->json(['success' => true, 'message' => $cupon, 'cantiReem' => $cantiReem], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'El cupón limitado'], 400);
        }
    }

    public function obtenerCupon(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'codigo' => 'required',
            'paquete' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $codigo = $request->input('codigo');
        $paquete = $request->input('paquete');


        // if ($paquete == 'rhnube-plus') {
        //     $paquete = 1;
        // }

        // if ($paquete == 'rhnube-remote') {
        //     $paquete = 2;
        // }

        // if ($paquete == 'rhnube-route') {
        //     $paquete = 3;
        // }

        $params = [
            'codigo' => $codigo,
            'paquete' => $paquete,
        ];

        $response = Http::post('https://beta.rhnube.com.pe/api/updateUseCupon', $params);
        dd( $response);


        if ($response->successful()) {
            // Obtener los datos de la respuesta
            $responseData = $response->json();
            // Hacer algo con los datos de la respuesta, si es necesario
            // Por ejemplo, devolver los datos como respuesta de tu controlador
            return response()->json($responseData);
        } else {
            // En caso de error en la solicitud, devolver un mensaje de error
            return response()->json(['error' => 'Error al consumir la API'], $response->status());
        }

        // try {


        //     $request->validate([
        //         'codigo' => 'required',
        //     ]);

        //     $codigo = $request->codigo;
        //     $cupon = Cupon::where('name_cupon', $codigo)->first();

        //     if (!$cupon) {
        //         return response()->json(['success' => false, 'message' => 'Cupón no encontrado']);
        //     }

        //     $fechaActual = now(); // Obtener la fecha y hora actual
        //     $fechaFinCupon = \Carbon\Carbon::parse($cupon->fecha_fin); // Convertir la fecha de finalización del cupón a un objeto Carbon

        //     if ($fechaFinCupon->isPast() && !$fechaFinCupon->isSameDay($fechaActual)) {
        //         return response()->json(['success' => false, 'message' => 'El cupón ha caducado']);
        //     }

        //     if ($cupon->cant_usada < $cupon->cantidad_uso) {
        //         $cantiReem = $cupon->cant_usada += 1;

        //         $detalles_cupones = detalle_cupones::select(
        //             'id_detalle_cupon_uso',
        //             'cupon_payment.codigo_cupon as cupon',
        //             'cupon_payment.id_cupon as id_cupon',
        //             'cupon_payment.name_cupon as name_cupon',
        //             'paquete_payment.paquete as paquete',
        //             'paquete_payment.id_paquete as id_paquete',
        //             'tipo_periodo.periodo as periodo',
        //             'tipo_periodo.id_tipo_periodo as id_tipo_periodo',
        //             'cupon_payment.fecha_inicio',
        //             'cupon_payment.fecha_fin',
        //             'detalle_cupon.ganancia',
        //             'cupon_payment.descuento',
        //             'cupon_payment.cantidad_uso',
        //             'detalle_cupon.link'
        //         )
        //             ->join('cupon_payment', 'detalle_cupon.id_cupon', '=', 'cupon_payment.id_cupon')
        //             ->join('paquete_payment', 'detalle_cupon.id_paquete', '=', 'paquete_payment.id_paquete')
        //             ->join('tipo_periodo', 'detalle_cupon.id_tipo_periodo', '=', 'tipo_periodo.id_tipo_periodo')
        //             ->where('detalle_cupon.id_cupon', $cupon->id_cupon)
        //             ->get();
        //         return response()->json(['success' => true, 'message' => $detalles_cupones, 'cantiReem' => $cantiReem], 200);
        //     } else {
        //         return response()->json(['success' => false, 'message' => 'El cupón limitado'], 400);
        //     }
        // } catch (ValidationException $validationException) {
        //     $errors = $validationException->errors();
        //     Log::error('Excepción en updateUseCupon - Validación: ' . json_encode($errors));
        //     return response()->json(['error' => 'Ha ocurrido un error de validación.', 'details' => $errors], 422);
        // } catch (\Exception $e) {
        //     Log::error('Excepción en updateUseCupon: ' . $e->getMessage());
        //     return response()->json(['error' => 'Ha ocurrido un error.', 'details' => $e->getMessage()], 500);
        // }
    }


    public function failure()
    {
        return view('welcome');
    }

    public function pending()
    {
        return view('welcome');
    }
}
