<?php

namespace App\Http\Controllers;

use App\AesCrypt;
use App\change_tokens;
use App\cupon;
use App\detalle_cupones;
use App\detalle_invitacion_influencer;
use App\InvitacionInfluencer;
use App\Mail\CuponAsignado;
use App\Mail\EmailChangeRequestMail;
use App\OperacionTransferencia;
use App\PaymentUsuario;
use App\propietarios;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InfluencerController extends Controller
{

    public function show(Request $request)
    {
        // Obtener el parámetro 'cid' de la URL
        $cid = $request->query('cid');

        if (!$cid) {
            abort(404);
        }

        // Buscar la invitación basada en el token 'cid'
        $invitacion = InvitacionInfluencer::where('token', $cid)->first();

        // Verificar la existencia de la invitación y sus condiciones
        if (
            !$invitacion ||
            now()->lt($invitacion->fecha_inicio) ||
            now()->gt($invitacion->fecha_fin) ||
            $invitacion->cantidad_acumulada >= $invitacion->cantidad_total
        ) {
            abort(404);
        }

        // Cerrar sesión del usuario actual y almacenar el token en la sesión
        $this->logout();
        session()->put('tokenRegistro', $cid);

        // Renderizar la vista 'influencers.registrosNuevos' con el token 'cid'
        return view('influencers.registrosNuevos', compact('cid'));
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
        $propietario = $responseData["propietario"][0];

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

            $emailExists = propietarios::where('correo', $logeado)->exists();

            if (!$emailExists) {
                return response()->json(['error' => 'El correo proporcionado no existe en la tabla propietarios.'], 422);
            }

            $propietario = propietarios::where('correo',  $logeado)->first();
            if (empty($propietario->banco) || empty($propietario->tipo_de_cuenta) || empty($propietario->cci) || empty($propietario->numero_de_cuenta)) {
                return response()->json(['error' => 'Falta rellenar sus datos bancarios.'], 422);
            }

            $totalGanancia = 0;
            foreach ($request->idCompra as $codigoCompra) {
                // Verificar si el código de compra no se repite
                $idCompraExists = PaymentUsuario::where('codigo_compra', $codigoCompra)->exists();
                if (!$idCompraExists) {
                    return response()->json(['error' => 'El código de compra ' . $codigoCompra . ' no existe.'], 422);
                }

                // Verificar si el código de compra existe y estado_transacion es 1
                $transaction = PaymentUsuario::where('codigo_compra', $codigoCompra)
                    ->where('estado_transacion', 1)
                    ->first();

                // Decodificar el JSON almacenado en 'dato_usuario'
                $dato_usuario = json_decode($transaction->dato_usuario, true);
                // Obtener la ganancia del JSON decodificado y sumar al total
                $ganancia = $dato_usuario['ganancia'] ?? 0;
                $totalGanancia += $ganancia;

                $transaction->estado_transacion = 2;
                $transaction->save();
            }

            $idCompraAsString = json_encode($request->idCompra);

            $operacion = OperacionTransferencia::create([
                'id_compras' => $idCompraAsString,
                'id_propietario' => $propietario->id_propietario,
                'totalPagar' => $totalGanancia,
                'fecha' => Carbon::now()->toDateString(),  // Obtiene la fecha actual en formato 'Y-m-d'
                'hora' => Carbon::now()->toTimeString(),   // Obtiene la hora actual en formato 'H:i:s'
                'estado' => 2,
            ]);

            return response()->json(['success' => true, 'message' => 'La actualización del estado de las transacciones fue exitosa.']);
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

    public function cancelarRetiro(Request $request)
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
                'idOperacion' => 'required'
            ]);

            $idOperacion =  $request->input('idOperacion');

            $operacion = OperacionTransferencia::where('id_operacion', $idOperacion)->first();
            if (!$operacion) {
                return response()->json(['success' => false, 'message' => 'La operación no existe.'], 200);
            }

            $compras = $operacion->id_compras;

            if (is_string($compras)) {
                $compras = json_decode($compras, true);
            }

            foreach ($compras as $compra) {

                $transaction = PaymentUsuario::where('codigo_compra', $compra)
                    ->first();
                $transaction->estado_transacion = 1;
                $transaction->save();
            }

            $operacion->delete();

            return response()->json(['success' => true, 'message' => 'Operación cancelada correctamente.'], 200);
        } catch (\Exception $e) {
            // Manejar otros errores inesperados
            Log::error('Error inesperado en eliminarRetiro: ' . $e->getMessage());
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

            $compras =  $request->input('idCompra');

            $emailExists = propietarios::where('correo', $logeado)->exists();
            if (!$emailExists) {
                return response()->json(['error' => 'El correo proporcionado no existe en la tabla propietarios.'], 200);
            }

            $totalGanancia = 0;

            foreach ($compras as $codigoCompra) {
                // Verificar si el código de compra existe
                $paymentUsuario = PaymentUsuario::where('codigo_compra', $codigoCompra)->first();
                if (!$paymentUsuario) {
                    return response()->json(['error' => 'El código de compra ' . $codigoCompra . ' no existe.'], 422);
                }

                // Decodificar el JSON almacenado en 'dato_usuario'
                $dato_usuario = json_decode($paymentUsuario->dato_usuario, true);
                // Obtener la ganancia del JSON decodificado y sumar al total
                $ganancia = $dato_usuario['ganancia'] ?? 0;
                $totalGanancia += $ganancia;
            }

            $cleanedTotalGanancia = str_replace(['$', ' '], '', $totalGanancia);

            if ($cleanedTotalGanancia <= 1.00) {
                return response()->json([
                    'success' => false,
                    'message' => 'El monto a retirar debe ser mayor a $1.00.',
                    'totalGanancia' => $cleanedTotalGanancia
                ], 200);
            }
            return response()->json(['success' => true, 'totalGanancia' => '$' . $totalGanancia], 200);
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
        if ($mensagge && isset($compraDeco["compras"])) {
            $compras = $compraDeco["compras"];
        }

        $operaciones = OperacionTransferencia::orderBy('id_operacion', 'desc')->get();
        return view('influencers.retiros', [
            'compras' => $compras,
            'operaciones' => $operaciones
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
            // Definir la URL de la imagen por defecto
            $imageUrl = url('influencers/images/imgDefault.png');

            // Determinar el campo a editar según la acción
            $accion = 'perfil';
            $campoEditar = $accion === 'perfil' ? 'foto_perfil' : 'foto_portada';

            // Obtener el usuario
            $obtenerUsuario = Propietarios::where('correo', $logeado)->first();

            if (!$obtenerUsuario) {
                // Si no se encuentra el usuario, responder con un error
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Obtener la imagen actual
            $imagenActual = $obtenerUsuario->$campoEditar;

            // Actualizar el campo de la imagen
            $obtenerUsuario->$campoEditar = $imageUrl;

            // Guardar los cambios y eliminar la imagen anterior del directorio si existe
            if ($obtenerUsuario->save()) {
                // Eliminar la imagen anterior del directorio si existe y es diferente a la imagen por defecto
                if ($imagenActual && $imagenActual !== $imageUrl) {
                    $nombreImagenActual = basename(parse_url($imagenActual, PHP_URL_PATH));
                    $rutaImagenActual = public_path('influencers/images/imagesPerfil/' . $nombreImagenActual);

                    if (file_exists($rutaImagenActual)) {
                        unlink($rutaImagenActual);
                    }
                }

                return $this->obtenerPropietario();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en eliminar la imagen',
                ], 502); // Código 502 para errores de la aplicación
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud.',
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

        try {
            // Validar el archivo cargado (tipo y tamaño)
            $validator = Validator::make($request->all(), [
                'image' => 'nullable|image|mimes:jpeg,png|max:2048', // Máximo de 2 MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422); // Código 422 para datos no válidos
            }

            // Obtener el usuario
            $obtenerUsuario = Propietarios::where('correo', $logeado)->first();

            if (!$obtenerUsuario) {
                // Si no se encuentra el usuario, responder con un error
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Obtener la imagen actual de perfil del usuario
            $imagenActual = $obtenerUsuario->foto_perfil;

            // Si no se proporciona una nueva imagen, mantener la actual
            if (!$request->hasFile('image')) {
                return $this->obtenerPropietario();
            }

            // Cargar la nueva imagen y validar sus dimensiones
            $file = $request->file('image');
            $image = Image::make($file);

            // Definir la ruta para guardar la imagen de perfil
            $filePath = 'influencers/images/imagesPerfil/'; // Ruta relativa
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // Nombre único para evitar conflictos
            $destinationPath = public_path($filePath); // Ruta absoluta

            // Asegurarse de que el directorio exista
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true); // Crear con permisos 0755 para mayor seguridad
            }

            // Si hay una imagen actual, eliminarla del directorio antes de guardar la nueva imagen
            if (!empty($imagenActual)) {
                $nombreImagenActual = basename(parse_url($imagenActual, PHP_URL_PATH));
                $rutaImagenActual = $destinationPath . $nombreImagenActual;

                if (file_exists($rutaImagenActual)) {
                    unlink($rutaImagenActual);
                }
            }

            // Mover el archivo al directorio deseado
            $file->move($destinationPath, $fileName);

            // Generar URL para acceder a la imagen desde el frontend
            $imageUrl = url($filePath . $fileName);

            // Asignar la nueva imagen al campo de la base de datos
            $obtenerUsuario->foto_perfil = $imageUrl;

            // Guardar los cambios
            if ($obtenerUsuario->save()) {
                return $this->obtenerPropietario();
            } else {
                return response()->json(['error' => 'Error al guardar el cambio'], 500);
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

            $obtenerUsuario = propietarios::where('correo', $logeado)->first();

            if ($obtenerUsuario) { // Si se encuentra un propietario
                $id_propietario = $obtenerUsuario->id_propietario; // Obtener el ID del propietario

                $cupon = cupon::where('id_propietario', $id_propietario)->first();
                $name = $cupon["name_cupon"];
                return response()->json([
                    'success' => true,
                    'cupon' => $name,
                ], 200);
            } else { // Si no se encuentra
                $this->logout();
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

            $compras = PaymentUsuario::where('dato_usuario', 'like', '%"codCupn":"' . $cupon . '"%')->get();

            return response()->json([
                'success' => true,
                'compras' => $compras,
            ], 200);
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

            $obtenerUsuario = propietarios::where('correo', $logeado)->get();

            if ($obtenerUsuario->isNotEmpty()) {
                // Desencriptar el campo "password" si está cifrado
                $propietarios = $obtenerUsuario->map(function ($propietario) {
                    if (isset($propietario->password)) {
                        try {
                            $propietario->password = AESCrypt::decrypt($propietario->password);
                        } catch (\Exception $e) {
                            // En caso de error en la desencriptación
                            Log::warning("Error al desencriptar la contraseña del usuario con ID: {$propietario->id_propietario}");
                            $propietario->password = 'Contraseña no disponible';
                        }
                    }
                    return $propietario;
                });


                if (session()->has('detalleUusario')) {
                    // Reemplazar el valor de 'detalleUsuario' con un nuevo valor
                    session()->put('detalleUusario', $propietarios["0"]);
                }

                return response()->json([
                    'success' => true,
                    'propietario' => $propietarios,
                ], 200);
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

            $obtenerUsuario = propietarios::where('correo', $user)->first();
            $id_propietario = $obtenerUsuario->id_propietario;

            if ($obtenerUsuario) {
                // Compara la contraseña proporcionada con la contraseña almacenada en la base de datos
                if (AesCrypt::decrypt($obtenerUsuario->password) === $password) {

                    $detalleCupon = cupon::where('id_propietario', $id_propietario)
                        ->with('propietario') // Cargar la relación con el propietario
                        ->select('id_cupon', 'codigo_cupon', 'name_cupon', 'cantidad_uso', 'cant_usada', 'fecha_inicio', 'fecha_fin', 'id_propietario', 'descuento', 'ganancia', 'link', 'accion')
                        ->first();

                    if ($detalleCupon && $detalleCupon->propietario) {
                        unset($detalleCupon->propietario->password); // Eliminar la propiedad 'password'
                    }

                    session()->put('logeado', $user);
                    session()->put('detalleUusario', $detalleCupon["propietario"]);

                    return response()->json([
                        'success' => true
                    ]);
                } else {
                    // Contraseña incorrecta
                    return response()->json(['sucess' => false, 'message' => 'Usuario o contraseña incorrectos'], 200);
                }
            } else {
                // Usuario no encontrado
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

    function crearCuponAutomatico()
    {
        $longitud = 6;
        $codigo = strtoupper(Str::random($longitud));

        while (cupon::where('name_cupon', $codigo)->exists()) {
            $codigo = strtoupper(Str::random($longitud));
        }

        return $codigo;
    }


    function cuponInfluencer(Request $request)
    {

        try {

            $request->validate([
                'email' => 'required',
            ]);

            $email = $request->input('email');


            $obtenerUsuario = propietarios::where('correo', $email)->first();

            if ($obtenerUsuario) { // Si se encuentra un propietario
                $id_propietario = $obtenerUsuario->id_propietario; // Obtener el ID del propietario

                $cupon = cupon::where('id_propietario', $id_propietario)->first();

                return response()->json(['cupon' => $cupon], 200); // Devuelve el ID del propietario
            } else { // Si no se encuentra
                return response()->json(['error' => 'Usuario no encontrado'], 404); // Devuelve mensaje de error
            }
        } catch (ValidationException $validationException) {
            // Manejar errores de validación
            $errors = $validationException->errors();
            Log::error('Excepción en cuponInfluencer: ' . json_encode($errors));
            return response()->json(['error' => 'Ha ocurrido un error de validación.', 'details' => $errors], 422);
            //return response()->json(['error' => 'Ha ocurrido un error de validación.' . $errors], 422);
        } catch (\Exception $e) {
            // Log de la excepción
            Log::error('Excepción en cuponInfluencer: ' . $e->getMessage());
            // Puedes redirigir a una página de error o hacer otro manejo según tus necesidades
            return response()->json(['error' => 'Ha ocurrido un error.', 'detalleInf' => $e->getMessage()], 500);
        }
    }

    public function registrarNuevo(Request $request)
    {
        try {

            $token = session()->get('tokenRegistro');

            $invitacion = InvitacionInfluencer::where('token', $token)->first();
            if (!$invitacion) {
                return response()->json(['success' => false, 'detalle' => 'No encontrado', 'message' => 'Token no encontrado'], 200);
            }

            $now = now();

            if ($now < $invitacion->fecha_inicio || $now > $invitacion->fecha_fin) {
                return response()->json(['success' => false, 'detalle' => 'No Por Fecha', 'message' => 'El token ha expirado.'], 200);
            }

            if ($invitacion->cantidad_acumulada >= $invitacion->cantidad_total) {
                return response()->json(['success' => false, 'detalle' => 'Cantidad', 'message' => 'El token ha expirado.'], 200);
            }


            $this->logout();

            // Validar la solicitud
            $validatedData = $request->validate([
                'codigo' => 'required|string|min:8|max:12',
                'nombres' => 'required|string',
                'apellido_paterno' => 'required|string',
                'apellido_materno' => 'required|string',
                'numero_movil' => 'required|string',
                'razon_social' => 'nullable|string',
                'password' => [
                    'required',
                    'string',
                    'min:8', // mínimo 8 caracteres
                    'regex:/[a-z]/', // al menos una letra minúscula
                    'regex:/[A-Z]/', // al menos una letra mayúscula
                    'regex:/[0-9]/', // al menos un número
                    'regex:/[@$!%*#?&]/' // al menos un carácter especial
                ],
                'cargo' => 'nullable|string',
                'email' => 'required|email',
                'facebook' => 'required|url',
                'linkedIn' => 'nullable|url',
                'instagram' => 'nullable|url',
                'tiktok' => 'nullable|url',
                'banco' => 'required|string',
                'tipCuenta' => 'required|string',
                'cci' => 'required|string|size:20', // ejemplo de longitud de 20 caracteres
                'nroCuenta' => 'required|string|min:10|max:18', // ejemplo de longitud de 15 caracteres
            ]);

            $codigo = $validatedData['codigo'];
            $nombres = $validatedData['nombres'];
            $apellido_paterno = $validatedData['apellido_paterno'];
            $apellido_materno = $validatedData['apellido_materno'];
            $razon_social = $validatedData['razon_social'];
            $numero_movil = $validatedData['numero_movil'];
            $password = $validatedData['password'];
            $cargo = $validatedData['cargo'];
            $email = $validatedData['email'];
            $facebook = $validatedData['facebook'];
            $linkedIn = $validatedData['linkedIn'];
            $instagram = $validatedData['instagram'];
            $tiktok = $validatedData['tiktok'];
            $banco = $validatedData['banco'];
            $tipCuenta = $validatedData['tipCuenta'];
            $cci = $validatedData['cci'];
            $nroCuenta = $validatedData['nroCuenta'];

            $redesSociales = "{'facebook': '$facebook', 'linkedIn': '$linkedIn', 'instagram': '$instagram', 'tiktok': '$tiktok'}";


            $codigoExistente = propietarios::where('codigo', $codigo)->exists();

            $correoExistente = propietarios::where('correo', $email)->exists();

            if ($codigoExistente) {
                return response()->json(['success' => false, 'message' => 'El código ya está en uso'], 200);
            }

            if ($correoExistente) {
                return response()->json(['success' => false, 'message' => 'El correo ya está en uso'], 200);
            }

            $propietarios = new propietarios();
            $propietarios->codigo = $codigo;
            $propietarios->nombres = $nombres;
            $propietarios->apellido_paterno = $apellido_paterno;
            $propietarios->apellido_materno = $apellido_materno;
            $propietarios->razon_social = $razon_social;
            $propietarios->correo = $email;
            $propietarios->numero_movil = $numero_movil;
            $propietarios->password = $password;
            $propietarios->cargo = $cargo;
            $propietarios->redes_sociales = $redesSociales;
            $propietarios->banco = $banco;
            $propietarios->tipo_de_cuenta = $tipCuenta;
            $propietarios->cci = $cci;
            $propietarios->numero_de_cuenta = $nroCuenta;

            if ($propietarios->save()) {

                $obtenerUsuario = propietarios::where('correo', $email)->get();

                $propietarios = $obtenerUsuario->map(function ($propietario) {
                    if (isset($propietario->password)) {
                        try {
                            $propietario->password = AESCrypt::decrypt($propietario->password);
                        } catch (\Exception $e) {
                            // En caso de error en la desencriptación
                            Log::warning("Error al desencriptar la contraseña del usuario con ID: {$propietario->id_propietario}");
                            $propietario->password = 'Contraseña no disponible';
                        }
                    }
                    return $propietario;
                });

                $propietarios = $propietarios->first();

                $idPropietario = $propietarios['id_propietario'];


                /*CREA CUPON*/
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

                $arrayPaq = ['1', '2', '3'];
                $arrayPer = ['1', '2', '3', '4'];
                $nombreCupon = $this->crearCuponAutomatico();
                $cantUso = 999;
                $dateInicio = now();
                $dateFin = $dateInicio->copy()->addYear();
                $redeem_by_timestamp = strtotime($dateFin->endOfDay());
                $desc = 10; // Ejemplo de descuento porcentual
                $ganancia = 10;

                if (cupon::where('name_cupon', $nombreCupon)->exists()) {
                    return response()->json(['success' => false, 'message' => 'El nombre del cupón ya está en uso']);
                }

                $stripeCoupon = \Stripe\Coupon::create([
                    'percent_off' => $desc,
                    'name' => $nombreCupon,
                    'max_redemptions' => $cantUso,
                    'redeem_by' => $redeem_by_timestamp,
                ]);

                $nuevoCupon = new cupon();
                $nuevoCupon->name_cupon = $nombreCupon;
                $nuevoCupon->codigo_cupon = $stripeCoupon->id;
                $nuevoCupon->cantidad_uso = $cantUso;
                $nuevoCupon->fecha_inicio = $dateInicio;
                $nuevoCupon->fecha_fin = $dateFin;
                $nuevoCupon->descuento = $desc;
                $nuevoCupon->ganancia = $ganancia;
                $nuevoCupon->id_propietario = $idPropietario;
                $nuevoCupon->link = 'https://payment.rhnube.com.pe/payment?cupon=' . $nombreCupon;
                $nuevoCupon->save();

                // Crear detalles del cupón
                if (Cupon::where('name_cupon', $nombreCupon)->exists()) {
                    $idCupon = Cupon::where('name_cupon', $nombreCupon)->pluck('id_cupon')->first();
                    foreach ($arrayPaq as $paquete) {
                        foreach ($arrayPer as $periodo) {
                            $detalleCuponUso = new detalle_cupones();
                            $detalleCuponUso->id_cupon = $idCupon;
                            $detalleCuponUso->id_paquete = $paquete;
                            $detalleCuponUso->id_tipo_periodo = $periodo;
                            $detalleCuponUso->save();
                        }
                    }
                }

                $idCupon = Cupon::where('name_cupon', $nombreCupon)->pluck('id_cupon')->first();
                $idInvitacion = InvitacionInfluencer::where('token', $token)->pluck('id_invitacion_influencer')->first();

                $invitacion = InvitacionInfluencer::where('token', $token)->first();

                if ($invitacion) {
                    // Incrementar 'cantidad_acumulada' por 1
                    $invitacion->cantidad_acumulada += 1;

                    // Guardar el cambio
                    $invitacion->save();
                }

                $detalleCuponUso = new detalle_invitacion_influencer();
                $detalleCuponUso->id_invitacion_influencer = $idInvitacion;
                $detalleCuponUso->id_propietario = $idPropietario;
                $detalleCuponUso->id_cupon = $idCupon;
                $detalleCuponUso->fecha_creacion = now();
                $detalleCuponUso->save();

                session()->put('logeado', $email);
                session()->put('detalleUusario', $propietarios);

                /*==========*/


                /*ENVIAR CORREO DE SU CUPON*/

                $cuponRequest = new Request(['email' => $email]);
                $cuponResponse = $this->cuponInfluencer($cuponRequest, true);
                $cuponData = $cuponResponse->getData(true);

                $nombres = $nombres;
                $cupon = $cuponData['cupon']['name_cupon'] ?? '';
                $porcentaje = $cuponData['cupon']['descuento'] ?? '';
                $link = $cuponData['cupon']['link'] ?? '';

                Mail::to($email)->send(new CuponAsignado($nombres, $cupon, $porcentaje, $link));

                /**=================*/
                return response()->json(['success' => true, 'message' => 'Propietario guardado exitosamente']);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el perfil: ',
                ], 500);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'validacion' => 'true',
                'message' => 'Datos inválidos: ' . json_encode($e->errors()),
            ], 200);
        } catch (\Exception $e) {
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
                'codigo' => 'required|string|min:8|max:12',
                'nombres' => 'required|string',
                'apellido_paterno' => 'required|string',
                'apellido_materno' => 'required|string',
                'numero_movil' => 'required|string',
                'razon_social' => 'nullable|string',
                'password' => [
                    'required',
                    'string',
                    'min:8', // mínimo 8 caracteres
                    'regex:/[a-z]/', // al menos una letra minúscula
                    'regex:/[A-Z]/', // al menos una letra mayúscula
                    'regex:/[0-9]/', // al menos un número
                    'regex:/[@$!%*#?&]/' // al menos un carácter especial
                ],
                'cargo' => 'nullable|string',
                'email' => 'required|email',
                'facebook' => 'required|url',
                'linkedIn' => 'nullable|url',
                'instagram' => 'nullable|url',
                'tiktok' => 'nullable|url',
                'banco' => 'required|string',
                'tipo_de_cuenta' => 'required|string',
                'cci' => 'required|string|size:20', // ejemplo de longitud de 20 caracteres
                'numero_de_cuenta' => 'required|string|min:10|max:18', // ejemplo de longitud de 15 caracteres
            ]);

            $input = $request->except([
                'codigo',
                'nombres',
                'apellido_paterno',
                'apellido_materno',
                'email',
                'numero_movil'
            ]);

            $usuario = Propietarios::where('correo', $logeado)->first();

            if (!$usuario) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $razon_social = $validatedData['razon_social'];
            $password = $validatedData['password'];
            $cargo = $validatedData['cargo'];
            $facebook = $validatedData['facebook'];
            $linkedIn = $validatedData['linkedIn'];
            $instagram = $validatedData['instagram'];
            $tiktok = $validatedData['tiktok'];
            $banco = $validatedData['banco'];
            $tipCuenta = $validatedData['tipo_de_cuenta'];
            $cci = $validatedData['cci'];
            $nroCuenta = $validatedData['numero_de_cuenta'];

            $redesSociales = "{'facebook': '$facebook', 'linkedIn': '$linkedIn', 'instagram': '$instagram', 'tiktok': '$tiktok'}";

            $usuario->razon_social = $razon_social;
            $usuario->password = AesCrypt::encrypt($password);
            $usuario->cargo = $cargo;
            $usuario->redes_sociales = $redesSociales;

            $usuario->banco = $banco;
            $usuario->tipo_de_cuenta = $tipCuenta;
            $usuario->numero_de_cuenta = $nroCuenta;
            $usuario->cci =  $cci;

            if ($usuario->save()) {
                return $this->obtenerPropietario();
            } else {
                return response()->json(['error' => 'Error al guardar los cambios'], 500);
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
}
