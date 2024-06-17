<?php

namespace App\Http\Controllers;

use App\AesCrypt;
use App\change_tokens;
use App\Mail\EmailChangeRequestMail;
use App\propietarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PropietarioController extends Controller
{


    private function generateUniqueCodigoValidacion()
    {
        do {
            $codigoValidacion = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
        } while (change_tokens::where('codigo_validacion', $codigoValidacion)->exists());

        return $codigoValidacion;
    }

    public function sendChangeEmailPasswordToken(Request $request)
    {
        try {

            // Obtener el usuario logeado o del request
            $logeado = session()->get('logeado') ?? $request->input('user');

            // Validar si no hay usuario logeado ni en el request
            if (!$logeado) {
                return response()->json(['success' => false, 'message' => 'Usuario no está logeado o no se proporcionó.'], 400);
            }

            // Definir el estado y título basado en la fuente del usuario logeado
            $status = session()->get('logeado') ? 'email-change' : 'pass-change';
            $title = $status === 'email-change' ? 'correo electrónico' : 'contraseña';

            // Buscar propietario por correo electrónico o ID
            $propietario = filter_var($logeado, FILTER_VALIDATE_EMAIL)
                ? propietarios::where('correo', $logeado)->firstOrFail()
                : propietarios::findOrFail($logeado);

            // Verificar si ya existe un token activo para este propietario
            $existingToken = change_tokens::where('id_propietario', $propietario->id_propietario)
                ->where('type_token', $status)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if ($existingToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya ha solicitado un cambio de ' . $title . '. Verifique su correo electrónico.'
                ], 201);
            }

            // Crear un nuevo token y código de validación
            $token = Str::random(60);
            $codigoValidacion = $this->generateUniqueCodigoValidacion();
            $expiresAt = Carbon::now()->addHour();

            // Guardar el token en la base de datos
            change_tokens::create([
                'id_propietario' => $propietario->id_propietario,
                'token' => $token,
                'type_token' => $status,
                'expires_at' => $expiresAt,
                'codigo_validacion' => $codigoValidacion
            ]);

            // Enviar el correo electrónico
            Mail::to($propietario->correo)
                ->send(new EmailChangeRequestMail($token, $status, $title, $codigoValidacion));

            return response()->json([
                'success' => true,
                'message' => 'Se ha enviado un correo electrónico para cambiar su ' . $title . '.',
                'token' => $token
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => "changeEmailToken " . $e->getMessage()], 500);
        }
    }




    // public function index()
    // {
    //     $usuario_organizacion = DB::table('usuario_organizacion as uso')
    //         ->where('uso.organi_id', '=', null)
    //         ->where('uso.user_id', '=', Auth::user()->id)
    //         ->get()->first();
    //     if ($usuario_organizacion->rol_id == 4) {
    //         return view('Licenciamientos.propietarios');
    //     } else {
    //         abort(404);
    //     }
    // }

    public function obtenerPropietarios()
    {
        try {
            $usuario_organizacion = DB::table('usuario_organizacion as uso')
                ->where('uso.organi_id', '=', null)
                ->where('uso.user_id', '=', Auth::user()->id)
                ->first();

            if ($usuario_organizacion && $usuario_organizacion->rol_id == 4) {
                $propietarios = propietarios::orderBy('codigo', 'asc')->get();

                // Desencriptar el campo password
                foreach ($propietarios as $propietario) {
                    $propietario->password = AESCrypt::decrypt($propietario->password);
                    $propietario->makeHidden('redes_sociales');
                }

                return response()->json($propietarios);
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            // Manejo de excepción
            return response()->json(['error' => 'Error al obtener propietarios: ' . $e->getMessage()], 500);
        }
    }


    public function accSaUpPropietario(Request $request)
    {
        try {
            $usuario_organizacion = DB::table('usuario_organizacion as uso')
                ->where('uso.organi_id', '=', null)
                ->where('uso.user_id', '=', Auth::user()->id)
                ->first();

            if ($usuario_organizacion && $usuario_organizacion->rol_id == 4) {

                $request->validate([
                    'codigo' => 'required',
                    'accion' => 'required|in:guardar,editar',
                    'nombres' => 'required',
                    'aPaterno' => 'required',
                    'aMaterno' => 'required',
                    'razonSocial' => 'required',
                    'correEl' => 'required',
                    'nMovil' => 'nullable',
                    'pass' => 'required',
                ]);

                $codigo = $request->input('codigo');
                $accion = $request->input('accion');
                $nombres = $request->input('nombres');
                $aPaterno = $request->input('aPaterno');
                $aMaterno = $request->input('aMaterno');
                $razonSocial = $request->input('razonSocial');
                $correEl = $request->input('correEl');
                $nMovil = $request->input('nMovil');
                $usuario = $request->input('user');
                $password = AesCrypt::encrypt($request->input('pass'));

                $usuarioExistente = propietarios::where('correo', $correEl)->exists();


                if ($accion == "guardar") {


                    $codigoExistente = propietarios::where('codigo', $codigo)->exists();

                    $correoExistente = propietarios::where('correo', $correEl)->exists();

                    if ($codigoExistente) {
                        return response()->json(['success' => false, 'message' => 'El código ya está en uso'], 200);
                    }

                    if ($usuarioExistente) {
                        return response()->json(['success' => false, 'message' => 'El usuario ya está en uso'], 200);
                    }

                    if ($correoExistente) {
                        return response()->json(['success' => false, 'message' => 'El correo ya está en uso'], 200);
                    }

                    $propietarios = new propietarios();
                    $propietarios->codigo = $codigo;
                    $propietarios->nombres = $nombres;
                    $propietarios->apellido_paterno = $aPaterno;
                    $propietarios->apellido_materno = $aMaterno;
                    $propietarios->razon_social = $razonSocial;
                    $propietarios->correo = $correEl;
                    $propietarios->numero_movil = $nMovil;
                    $propietarios->password = $password;


                    if ($propietarios->save()) {
                        return response()->json(['success' => true, 'message' => 'Propietario guardado exitosamente']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Error al guardar el propietario'], 500);
                    }
                }

                if ($accion == "editar") {


                    $idPro = $request->input('idPro');

                    $propietarios = propietarios::where('id_propietario', $idPro)->firstOrFail();

                    if ($codigo != $propietarios->codigo) {
                        $codigoExistente = propietarios::where('codigo', $codigo)->exists();
                        if ($codigoExistente) {
                            return response()->json(['success' => false, 'message' => 'El código ya está en uso'], 200);
                        }
                    }

                    $propietarios->codigo = $codigo;
                    $propietarios->nombres = $nombres;
                    $propietarios->apellido_paterno = $aPaterno;
                    $propietarios->apellido_materno = $aMaterno;
                    $propietarios->razon_social = $razonSocial;
                    $propietarios->correo = $correEl;
                    $propietarios->numero_movil = $nMovil;
                    $propietarios->password = $password;

                    if ($propietarios->save()) {
                        return response()->json(['success' => true, 'message' => 'Propietario guardado exitosamente']);
                    } else {
                        return response()->json(['success' => false, 'message' => 'Error al guardar el propietario'], 500);
                    }
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
