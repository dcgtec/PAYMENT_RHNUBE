<?php

namespace App\Http\Controllers;

use App\CategoryPlan;
use App\Mail\CompraExitosa;
use Illuminate\Http\Request;
use App\Plan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Stripe\Order;

class PaymentController extends Controller
{

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

            // Validación de seguridad para Stripe API Key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Configuración de impuestos según el país
            $taxes = [];
            if ($country == "PE") {
                $taxes = ['txr_1OMyoaCbKz5YJFE3ajJh9S4U'];
            }

            // Crear sesión de checkout para la suscripción
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $plan->stripe_plan,
                    'quantity' => $quanty,
                    'tax_rates' => $taxes
                ]],
                'mode' => 'subscription',
                'success_url' => route('success'),
                'cancel_url' => route('index'),
            ]);

            // Guardar la ID de la sesión de Stripe en la sesión de Laravel
            session([
                'stripe_session_id' => $session->id,
                'cantEmpleados' => $quanty,
                'plan' => $idplan
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


    public function success(Order $order, Request $request)
    {
        // Obtener el ID de la sesión de Stripe de la sesión
        $stripeSessionId = session('stripe_session_id');
        $plan = session('plan');
        $cantEmpleados = session('cantEmpleados');

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
        $customerName = explode(' ', $stripeSession->customer_details->name)[0];
        $fecha = \Carbon\Carbon::parse($stripeSession->created)->format('Y-m-d H:i:s');
        $monto = $stripeSession->amount_total / 100;

        $jsonCompra = [
            'namePlan' => $name, 'cantEmpleados' => $cantEmpleados, 'categorySlug' => $categorySlug, 'customerName' => $customerName,
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


    public function failure()
    {
        return view('welcome');
    }

    public function pending()
    {
        return view('welcome');
    }
}
