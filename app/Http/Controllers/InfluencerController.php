<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Intervention\Image\Facades\Image;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class InfluencerController extends Controller
{

    public function show(Request $request)
    {
        $cid = $request->query('cid'); // Obteniendo el parámetro 'cid' de la URL

        if (!$cid) {
            // Si 'cid' no está presente, devolver una respuesta de error o redirigir
            abort(404);
        }

        $response = Http::post('https://rhnube.com.pe/api/validarToken', [
            'token' => $cid
        ]);


        // Si la API responde con 1, permitir el acceso
        if ($response->json() === 1) {
            $this->logout();
            session()->put('tokenRegistro', $cid);
            return view('influencers.registrosNuevos', compact('cid'));
        }


        // if (session()->has('tokenRegistro')) {
        //     // Reemplazar el valor de 'detalleUsuario' con un nuevo valor
        //     session()->forget('tokenRegistro');
        // }

        abort(404);
    }

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

    public function logout()
    {
        session()->forget('logeado');
        session()->forget('detalleUusario');
        return redirect('/iniciarSesion');
    }

    public function micupon()
    {
        $cupon = $this->obtenerCupon();

        return view('influencers.micupon', compact('cupon'));
    }

    public function retirarDinero(Request $request)
    {
        try {
            $logeado = session()->get('logeado');
            if (!$logeado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado',
                ], 401); // Código 401 para no autorizado
            }

            $request->validate([
                'idCompra' => 'required|array'
            ]);

            $data = [
                'email' => $logeado,
                'idCompra' => $request->input('idCompra')
            ];

            $response = Http::post('https://rhnube.com.pe/api/changeStatusCobrado', $data);

            if ($response->successful()) {
                // Decodifica la respuesta JSON
                $responseData = $response->json();
                // Retorna la respuesta decodificada (puedes personalizar esto según tus necesidades)
                return response()->json($responseData, 200);
            } else {
                // Maneja el error si la solicitud no fue exitosa
                return response()->json(['error' => '' . $response["error"]], $response->status());
            }
        } catch (\Exception $e) {
            // Manejar otros errores inesperados
            Log::error('Error inesperado en retarDinero: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado.',
                'details' => $e->getMessage(),
            ], 500); // Código 500 para error interno
        }
    }

    public function cambiarEstadoPorCobrar(Request $request)
    {
        try {

            $logeado = session()->get('logeado');
            if (!$logeado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado',
                ], 401); // Código 401 para no autorizado
            }

            $request->validate([
                'idCompra' => 'required|array'
            ]);


            $data = [
                'email' => $logeado,
                'idCompra' => $request->input('idCompra')
            ];

            $response = Http::post('https://rhnube.com.pe/api/obtenerSumaRetiro', $data);

            // Verifica si la solicitud fue exitosa
            if ($response->successful()) {
                // Decodifica la respuesta JSON
                $responseData = $response->json();
                $cleanedTotalGanancia = str_replace(['$', ' '], '', $responseData["totalGanancia"]);

                if ($cleanedTotalGanancia <= 1.00) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El monto a retirar debe ser mayor a $1.00.',
                        'totalGanancia' => $cleanedTotalGanancia
                    ], 200);
                }

                // Retorna la respuesta decodificada (puedes personalizar esto según tus necesidades)
                return response()->json(['success' => true, 'success' => $responseData], 200);
            } else {
                // Maneja el error si la solicitud no fue exitosa
                return response()->json(['error' => 'Error en la solicitud a la API'], $response->status());
            }
        } catch (\Exception $e) {
            // Manejar otros errores inesperados
            Log::error('Error inesperado en cambiarEstadoPorCobrar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error inesperado.',
                'details' => $e->getMessage(),
            ], 500); // Código 500 para error interno
        }
    }

    public function retiros()
    {
        $comprasProcesadas = $this->obtenerCompras();
        $compraDeco = json_decode($comprasProcesadas->getContent(), true);
        $mensagge =  $compraDeco["success"];

        $compras = [];
        if ($mensagge && isset($compraDeco["compras"]["compras"])) {
            $compras = $compraDeco["compras"]["compras"];
        }


        return view('influencers.retiros', [
            'compras' => $compras
        ]);
    }

    public function deletePthoPerfil(Request $request)
    {
        // Verificar si el usuario está autenticado
        $logeado = session()->get('logeado');
        if (!$logeado) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401); // Código 401 para no autorizado
        }


        try {

            $imageUrl = url('influencers/images/imgDefault.png');

            $accion = 'perfil';

            $imgPerfil = Http::post('https://rhnube.com.pe/api/cambiarImgPerfil', [
                'accion' => $accion,
                'email' => $logeado,
                'imgPerfil' => $imageUrl
            ]);


            if ($imgPerfil->successful()) {
                return $this->obtenerPropietario();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la solicitud externa',
                ], 502); // Código 502 para error de gateway
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la imagen.',
            ], 500); // Código 500 para errores del servidor
        }
    }

    public function uploadImage(Request $request)
    {
        // Verificar si el usuario está autenticado
        $logeado = session()->get('logeado');
        if (!$logeado) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401); // Código 401 para no autorizado
        }

        // Validar el archivo cargado (tipo y tamaño)
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png|max:2048', // Máximo de 2 MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422); // Código 422 para datos no válidos
        }

        try {
            // Cargar la imagen y validar sus dimensiones
            $file = $request->file('image');
            $image = Image::make($file);

            if ($image->width() !== $image->height()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La imagen debe ser cuadrada.',
                ], 422);
            }

            // Definir la ruta para guardar la imagen
            $filePath = 'influencers/images/imagesPerfil/'; // Ruta relativa
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // Nombre único para evitar conflictos
            $destinationPath = public_path($filePath); // Ruta absoluta

            // Asegurarse de que el directorio exista
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Crear con permisos 0755 para mayor seguridad
            }

            // Mover el archivo al directorio deseado
            $file->move($destinationPath, $fileName);

            // Generar URL para acceder a la imagen desde el frontend
            $imageUrl = url($filePath . $fileName);

            // Realizar solicitud externa para actualizar el perfil
            $accion = 'perfil';

            $imgPerfil = Http::post('https://rhnube.com.pe/api/cambiarImgPerfil', [
                'accion' => $accion,
                'email' => $logeado,
                'imgPerfil' => $imageUrl
            ]);

            if ($imgPerfil->successful()) {
                return $this->obtenerPropietario();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la solicitud externa',
                ], 502); // Código 502 para error de gateway
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la imagen.',
            ], 500); // Código 500 para errores del servidor
        }
    }

    public function obtenerCupon()
    {
        $logeado = session()->get('logeado');
        if (!$logeado) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
            ], 401); // Código 401 para no autorizado
        }

        try {

            $obtenerCupon = Http::post('https://rhnube.com.pe/api/cuponInfluencer', [
                'email' => $logeado
            ]);


            if ($obtenerCupon->successful()) {
                // Si la solicitud es exitosa, obtener el cuerpo de la respuesta
                $data = $obtenerCupon->json(); // Asumimos que la respuesta es JSON
                $cupon = $data["cupon"]["name_cupon"];
                return response()->json([
                    'success' => true,
                    'cupon' => $cupon,
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

            $cupon = $this->obtenerCupon();
            $responseData = json_decode($cupon->getContent(), true);
            $cupon = $responseData["cupon"];

            $obtenerComprasPorCupon = Http::post('https://rhnube.com.pe/api/obtenerComprasPorCupon', [
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
            $response = Http::post('https://rhnube.com.pe/api/obtenerPropietario', [
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
            $response = Http::post('https://rhnube.com.pe/api/logearUser', [
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

    public function registrarNuevo(Request $request)
    {
        try {

            $token = session()->get('tokenRegistro');


            $response = Http::post('https://rhnube.com.pe/api/validarToken', [
                'token' =>  $token
            ]);



            // Si la API responde con 1, permitir el acceso
            if ($response->json() === 1) {

                $this->logout();

                // Validar la solicitud
                $validatedData = $request->validate([
                    'codigo' => 'required|string',
                    'nombres' => 'required|string',
                    'razon_social' => 'nullable',
                    'apellido_paterno' => 'required|string',
                    'apellido_materno' => 'required|string',
                    'numero_movil' => 'required|string',
                    'password' => 'required|string',
                    'cargo' => 'nullable|string',
                    'email' => 'required|email',
                    'redes_sociales' => 'nullable|string',
                    'banco' => 'nullable',
                    'tipCuenta' => 'nullable',
                    'cci' => 'nullable',
                    'nroCuenta' => 'nullable',
                ]);

                $faceboook = $request->input('facebook');
                $linkedIn = $request->input('linkedIn');
                $instagram = $request->input('instagram');
                $tiktok = $request->input('tiktok');

                $banco = $request->input('banco');
                $tipo_de_cuenta = $request->input('tipCuenta');
                $cci = $request->input('cci');
                $numero_de_cuenta = $request->input('nroCuenta');

                $redesSociales = "{'facebook': '$faceboook', 'linkedIn': '$linkedIn', 'instagram': '$instagram', 'tiktok': '$tiktok'}";

                // Enviar una solicitud POST a la API externa para guardar perfil
                $response = Http::post('https://rhnube.com.pe/api/guardarPerfil', [
                    'codigo' => $validatedData['codigo'],
                    'nombres' => $validatedData['nombres'],
                    'apellido_paterno' => $validatedData['apellido_paterno'],
                    'apellido_materno' => $validatedData['apellido_materno'],
                    'redes_sociales' => $redesSociales,
                    'email' => $validatedData['email'],
                    'password' => $validatedData['password'],
                    'numero_movil' => $validatedData['numero_movil'],
                    'razon_social' => $validatedData['razon_social'],

                    'banco' =>  $banco,
                    'tipo_de_cuenta' => $tipo_de_cuenta,
                    'cci' => $cci,
                    'numero_de_cuenta' =>   $numero_de_cuenta,


                    'cargo' => $validatedData['cargo'] ?? null, // Opcional
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();

                    // Verificar la estructura de la respuesta antes de acceder a las claves
                    if (isset($responseData['success']) && isset($responseData['message'])) {
                        if ($responseData['success']) {
                            $mensaje = $responseData['message'];

                            // Obtener propietario por correo electrónico
                            $obtePro = Http::post('https://rhnube.com.pe/api/obtenerPropietario', [
                                'email' => $validatedData['email']
                            ]);

                            if ($obtePro->successful()) {
                                $data = $obtePro->json();

                                // Verificar si la clave existe y tiene datos
                                if (isset($data['propietario'][0]['id_propietario'])) {

                                    $idPropietario = $data['propietario'][0]['id_propietario'];
                                    // Valor por defecto si no existe
                                    $propietario = $data["propietario"]["0"];
                                    // Crear cupón con los datos obtenidos
                                    $crearCupon = Http::post('https://rhnube.com.pe/api/crearCupon', [
                                        'propietario' => $idPropietario,
                                        'token' => $token
                                    ]);

                                    if ($crearCupon->successful()) {
                                        $cuponData = $crearCupon->json();

                                        if (isset($cuponData['success']) && $cuponData['success']) {
                                            session()->put('logeado', $validatedData['email']);
                                            session()->put('detalleUusario', $propietario);


                                            $emailCupon = Http::post('https://rhnube.com.pe/api/emailCupon', [
                                                'correo' => $validatedData['email'],
                                                'nombre' => $validatedData['nombres']
                                            ]);

                                            return response()->json([
                                                'success' => true,
                                                'message' => $mensaje,
                                            ], 200);
                                        } else {
                                            return response()->json([
                                                'success' => false,
                                                'message' => 'No se pudo crear el cupón',
                                            ], 500);
                                        }
                                    } else {
                                        return response()->json([
                                            'success' => false,
                                            'message' => 'Error al crear el cupón',
                                        ], 500);
                                    }
                                } else {
                                    return response()->json([
                                        'success' => false,
                                        'message' => $data["mensaje"],
                                    ], 200);
                                }
                            } else {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Error al obtener el propietario',
                                ], 500);
                            }
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' =>  $responseData['message'],
                            ], 200);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' =>  'Error al obtener el propietario',
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al guardar el perfil: ' . $response->body(),
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Token Caducado',
                ], 200);
            }
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


    public function changeEmailToken(Request $request)
    {
        try {

            $idPropietario = null;
            $status = 'email-change';
            $title = 'correo electrónico';

            if ($request->user) {
                $idPropietario = $request->user;
                $status = 'pass-change';
                $title = 'contraseña';
            } elseif (session()->get('logeado')) {
                $logeado = session()->get('logeado');

                if (empty($logeado)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró información de inicio de sesión.',
                    ], 400);
                }

                $responseProp = $this->obtenerPropietario();
                $responseData = json_decode($responseProp->getContent(), true);
                $idPropietario = $responseData["propietario"]["propietario"]["0"]["id_propietario"];
            }

            if (!$idPropietario) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID del propietario no proporcionado.',
                ], 400);
            }

            $response = Http::post('https://rhnube.com.pe/api/statusChangeToken', [
                'idPropietario' => $idPropietario,
                'status' => $status,
                'title' => $title,
            ]);

            $respuesta = $response->json();
            return response()->json([
                'success' => $respuesta["success"],
                'message' => $respuesta["message"],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos: ' . json_encode($e->errors()),
            ], 400);
        } catch (RequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con la API externa changeToken: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado changeToken: ' . $e->getMessage(),
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
                'banco' => 'required',
                'tipo_de_cuenta' => 'required',
                'cci' => 'required',
                'numero_de_cuenta' => 'required',
            ]);

            $faceboook = $request->input('facebook');
            $linkedIn = $request->input('linkedIn');
            $instagram = $request->input('instagram');
            $tiktok = $request->input('tiktok');
            $password = $request->input('password');
            $actualizarCorreo =  $request->input('email');

            $banco = $request->input('banco');
            $tipo_de_cuenta = $request->input('tipo_de_cuenta');
            $cci = $request->input('cci');
            $numero_de_cuenta = $request->input('numero_de_cuenta');

            $redesSociales = "{'facebook': '$faceboook', 'linkedIn': '$linkedIn', 'instagram': '$instagram', 'tiktok': '$tiktok'}";

            // Fusionar datos validados con el email desde la sesión
            $dataToSend = array_merge($validatedData, [
                'email' => $logeado,
                'actualizarCorreo' =>  $actualizarCorreo,
                'password' => $password,
                'banco' => $banco,
                'tipo_de_cuenta' => $tipo_de_cuenta,
                'cci' => $cci,
                'numero_de_cuenta' => $numero_de_cuenta,
                'redes_sociales' => $redesSociales
            ]);



            // Enviar datos a la API externa
            $response = Http::post('https://rhnube.com.pe/api/editarPerfil', $dataToSend);


            // Verificar si la solicitud fue exitosa
            if ($response->failed()) {
                throw new HttpException(500, "La solicitud a la API externa falló: " . $response->body());
            }

            // if (session()->has('logeado')) {
            //     // Reemplazar el valor de 'detalleUsuario' con un nuevo valor
            //     session()->put('logeado', $actualizarCorreo);
            // }
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
