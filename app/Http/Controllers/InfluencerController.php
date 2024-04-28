<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InfluencerController extends Controller
{

    public function index()
    {
        return view('influencers.index');


        if (!session()->has('logeado')) {
            return redirect('/iniciarSesion'); // Ajusta la ruta del login según corresponda
        }

        return redirect('/perfil');
    }

    public function perfil()
    {

        $response = $this->obtenerPropietario();
        $responseData = json_decode($response->getContent(), true);
        $propietario = $responseData["propietario"]["propietario"][0];

        return view('influencers.perfil', compact('propietario'));
    }

    public function referidos()
    {

        $response = $this->obtenerCompras();
        $responseData = $response->getContent();
        return view('influencers.referidos', compact('responseData'));
    }

    public function micupon()
    {

        return view('influencers.micupon');
    }

    public function retiros()
    {
        return view('influencers.retiros');
    }


    public function obtenerCompras()
    {
        $logeado = session()->get('logeado');
        if (!$logeado) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401); // Código 401 para no autorizado
        }

        try {

            $cupon = 'PRACTICAR';
            $obtenerComprasPorCupon = Http::post('https://beta.rhnube.com.pe/api/obtenerComprasPorCupon', [
                'cupon' => $cupon
            ]);


            if ($obtenerComprasPorCupon->successful()) {
                // Si la solicitud es exitosa, obtener el cuerpo de la respuesta
                $data = $obtenerComprasPorCupon->json(); // Asumimos que la respuesta es JSON

                return response()->json([
                    'success' => true,
                    'compras' => $data,
                ], 200); // Código 200 para éxito
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la solicitud externa',
                ], 502); // Código 502 para error de gateway
            }
        } catch (RequestException $e) {
            // Manejar errores de solicitud
            Log::error('Error al conectar con la API externa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con la API externa.',
                'details' => $e->getMessage(),
            ], 500); // Código 500 para error interno
        } catch (\Exception $e) {
            // Manejar otros errores inesperados
            Log::error('Error inesperado en obtenerPropietario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado.',
                'details' => $e->getMessage(),
            ], 500); // Código 500 para error interno
        }
    }


    public function obtenerPropietario()
    {
        // Verificar si el usuario está logeado
        $logeado = session()->get('logeado');

        if (!$logeado) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401); // Código 401 para no autorizado
        }


        try {
            // Realizar la solicitud a la API externa
            $response = Http::post('https://beta.rhnube.com.pe/api/obtenerPropietario', [
                'email' => $logeado
            ]);

            if ($response->successful()) {
                // Si la solicitud es exitosa, obtener el cuerpo de la respuesta
                $data = $response->json(); // Asumimos que la respuesta es JSON

                if (session()->has('detalleUusario')) {
                    // Reemplazar el valor de 'detalleUsuario' con un nuevo valor
                    session()->put('detalleUusario', $data["propietario"][0]);
                }

                return response()->json([
                    'success' => true,
                    'propietario' => $data,
                ], 200); // Código 200 para éxito
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la solicitud externa',
                ], 502); // Código 502 para error de gateway
            }
        } catch (RequestException $e) {
            // Manejar errores de solicitud
            Log::error('Error al conectar con la API externa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con la API externa.',
                'details' => $e->getMessage(),
            ], 500); // Código 500 para error interno
        } catch (\Exception $e) {
            // Manejar otros errores inesperados
            Log::error('Error inesperado en obtenerPropietario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado.',
                'details' => $e->getMessage(),
            ], 500); // Código 500 para error interno
        }
    }

    public function login(Request $request)
    {
        // Definir reglas de validación
        $rules = [
            'user' => 'required|email',
            'password' => 'required',
            'recaptcha' => 'required',
        ];

        // Definir mensajes personalizados
        $messages = [
            'user.required' => 'El correo electrónico es obligatorio.',
            'user.email' => 'Debe ser un correo electrónico válido.', // Mensaje personalizado para el campo "user"
            'password.required' => 'La contraseña es obligatoria.',
            'recaptcha.required' => 'El captcha es obligatorio.',
        ];

        // Realizar la validación con reglas y mensajes personalizados
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Si falla la validación, devolver errores personalizados
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(), // Devuelve los errores con mensajes personalizados
            ]); // Código de estado para errores de validación
        }

        // Obtén valores validados
        $validated = $validator->validated();

        // Ahora puedes obtener los valores individuales del array validado
        $user = $validated['user'];
        $password = $validated['password'];

        try {
            // Hacer una solicitud POST a la API externa
            $response = Http::post('https://beta.rhnube.com.pe/api/logearUser', [
                'user' => $user,
                'password' => $password,
            ]);

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                $data = $response->json();

                // Guardar el nuevo dato en la sesión
                session()->put('logeado', $user);
                session()->put('detalleUusario', $data["detalle_pr"]["propietario"]);
                return response()->json([
                    'success' => true
                ]);
            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                ]);
            }
        } catch (RequestException $e) {
            // Manejar errores de solicitud
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con la API externa: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            // Manejar otros errores
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function actualizarPerfil(Request $request)
    {

        try {
            // Obtener el valor de email desde la sesión
            $logeado = session()->get('logeado');




            // Verificar si el valor de 'logeado' está presente
            if (empty($logeado)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información de inicio de sesión.',
                ], 400);
            }

            // Validar datos de entrada (sin el campo 'email' ya que proviene de la sesión)
            $validatedData = $request->validate([
                'codigo' => 'nullable',
                'nombres' => 'required',
                'razon_social' => 'nullable',
                'apellido_paterno' => 'required',
                'apellido_materno' => 'required',
                'numero_movil' => 'nullable',
                'password' => 'required',
                'cargo' => 'nullable',
                'email' => 'required',
                'redes_sociales' => 'nullable',
            ]);

            $faceboook = $request->input('facebook');
            $linkedIn = $request->input('linkedIn');
            $instagram = $request->input('instagram');
            $tiktok = $request->input('tiktok');
            $actualizarCorreo =  $request->input('email');

            $redesSociales = "{'facebook': '$faceboook', 'linkedIn': '$linkedIn', 'instagram': '$instagram', 'tiktok': '$tiktok'}";


            // Fusionar datos validados con el email desde la sesión
            $dataToSend = array_merge($validatedData, ['email' => $logeado, 'actualizarCorreo' =>  $actualizarCorreo, 'redes_sociales' => $redesSociales]);





            // Enviar datos a la API externa
            $response = Http::post('https://beta.rhnube.com.pe/api/editarPerfil', $dataToSend);


            // Verificar si la solicitud fue exitosa
            if ($response->failed()) {
                throw new HttpException(500, "La solicitud a la API externa falló: " . $response->body());
            }

            if (session()->has('logeado')) {
                // Reemplazar el valor de 'detalleUsuario' con un nuevo valor
                session()->put('logeado', $actualizarCorreo);
            }
            return $this->obtenerPropietario();
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos: ' . json_encode($e->errors()),
            ], 400);
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con la API externa: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado: ' . $e->getMessage(),
            ], 500);
        }
    }
}
