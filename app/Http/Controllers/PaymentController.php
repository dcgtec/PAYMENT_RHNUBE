<?php

namespace App\Http\Controllers;

use App\CategoryPlan;
use App\cupon;
use App\detalle_cupones;
use App\Mail\CompraExitosa;
use App\Mail\CompraExitosoPayment;
use App\PaymentUsuario;
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

            $dataCupon = cupon::where('name_cupon', $cupon)->first();

            if (!$dataCupon) {
                throw new \Exception('Cupón no encontrado.');
            }

            $fechaActual = now(); // Obtener la fecha y hora actual
            $fechaFinCupon = \Carbon\Carbon::parse($dataCupon->fecha_fin); // Convertir la fecha de finalización del cupón a un objeto Carbon

            if ($fechaFinCupon->isPast() && !$fechaFinCupon->isSameDay($fechaActual)) {
                throw new \Exception('El cupón ha caducado.');
            }

            if ($dataCupon->cant_usada < $dataCupon->cantidad_uso) {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $codCupn = $dataCupon["codigo_cupon"];
                $cant_usada =  $dataCupon["cant_usada"];

                // Configuración de impuestos según el país
                $taxes = [];
                if ($country == "PE") {
                    $taxes = ['txr_1OMyoaCbKz5YJFE3ajJh9S4U'];
                }
                // Prod = txr_1ORzFnCbKz5YJFE3k4IpT5vR
                // Prueba = txr_1OMyoaCbKz5YJFE3ajJh9S4U

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
            } else {
                throw new \Exception('El cupón limitado.');
            }
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

                Mail::to($correoDestino)->send(new CompraExitosoPayment($codigoGenerado, $customerName, $fecha, $monto));
                // Respuesta de éxito
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
        $cupones = Cupon::with(['detallesCupones.paquete', 'detallesCupones.periodo'])
            ->where('accion', '1')
            ->where('name_cupon', $codCupn)
            ->get();

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
            unset($cupon->password);
            unset($cupon->usuario);

            $cupon->paquetes = array_values($paquetes);
            $cupon->periodos = array_values($periodos);
            return $cupon;
        });

        $datos = $cupones->first();

        $porGanancia = round($datos["ganancia"], 2);
        $ganancia = round($monto * $porGanancia / 100, 2);


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

        $estadoCodigo = PaymentUsuario::where('codigo_compra', $codigoGenerado)->get();

        if ($estadoCodigo->isEmpty()) {
            $nuevaCompra = new PaymentUsuario();
            $nuevaCompra->codigo_compra = $codigoGenerado;
            $nuevaCompra->dato_usuario = $jsonDetalleCompra;
            $nuevaCompra->code_stripe =  $stripeSession->id;
            $nuevaCompra->correo = $stripeSession->customer_details->email;
            $nuevaCompra->fecha_compra =  $fecha;
            $nuevaCompra->save();

            $this->actualizarCodigo($codCupn);

            Mail::to($stripeSession->customer_details->email)->send(new CompraExitosoPayment($codigoGenerado, $customerName, $fecha, $monto));
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
        // Validar los parámetros de entrada
        $validator = Validator::make($request->all(), [
            'codigo' => 'required',
            'paquete' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $codigo = $request->input('codigo');
        $paquete = $request->input('paquete');

        // Mapear los nombres de los paquetes a sus respectivos IDs
        $paqueteIds = [
            'rhnube-plus' => 1,
            'rhnube-remote' => 2,
            'rhnube-route' => 3,
        ];

        // Convertir $paquete a entero si es un nombre de paquete
        if (isset($paqueteIds[$paquete])) {
            $paqueteId = $paqueteIds[$paquete];
        } else {
            // Si $paquete es '0', mantenerlo como entero
            $paqueteId = (int) $paquete;
        }

        $cupon = Cupon::where('name_cupon', $codigo)->first();

        if (!$cupon) {
            return response()->json(['success' => false, 'message' => 'Cupón no encontrado']);
        }

        $fechaActual = now();
        $fechaFinCupon = \Carbon\Carbon::parse($cupon->fecha_fin);

        if ($fechaFinCupon->isPast() && !$fechaFinCupon->isSameDay($fechaActual)) {
            return response()->json(['success' => false, 'message' => 'El cupón ha caducado']);
        }

        if ($cupon->cant_usada >= $cupon->cantidad_uso) {
            return response()->json(['success' => false, 'message' => 'El cupón ha alcanzado su límite de uso'], 400);
        }

        if ($paqueteId === 0) {
            // Obtener todos los detalles de cupones para todos los paquetes
            $cupones = Cupon::with(['detallesCupones.paquete', 'detallesCupones.periodo'])
                ->where('accion', '1')
                ->where('name_cupon', $codigo)
                ->get()
                ->transform(function ($cupon) {
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

            return response()->json(['success' => true, 'message' => $cupones, 'cantiReem' => $cupon->cant_usada], 200);
        }

        // Obtener detalles de cupones solo para el paquete específico
        $detallesCupones = detalle_cupones::select(
            'id_detalle_cupon_uso',
            'cupon_payment.codigo_cupon as cupon',
            'cupon_payment.id_cupon as id_cupon',
            'cupon_payment.name_cupon as name_cupon',
            'paquete_payment.paquete as paquete',
            'paquete_payment.id_paquete as id_paquete',
            'tipo_periodo.periodo as periodo',
            'tipo_periodo.id_tipo_periodo as id_tipo_periodo',
            'cupon_payment.fecha_inicio',
            'cupon_payment.fecha_fin',
            'cupon_payment.ganancia',
            'cupon_payment.descuento',
            'cupon_payment.cantidad_uso',
            'cupon_payment.link'
        )
            ->join('cupon_payment', 'detalle_cupon.id_cupon', '=', 'cupon_payment.id_cupon')
            ->join('paquete_payment', 'detalle_cupon.id_paquete', '=', 'paquete_payment.id_paquete')
            ->join('tipo_periodo', 'detalle_cupon.id_tipo_periodo', '=', 'tipo_periodo.id_tipo_periodo')
            ->where('detalle_cupon.id_cupon', $cupon->id_cupon)
            ->where('detalle_cupon.id_paquete', $paqueteId)
            ->get();

        return response()->json(['success' => true, 'message' => $detallesCupones, 'cantiReem' => $cupon->cant_usada], 200);
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
