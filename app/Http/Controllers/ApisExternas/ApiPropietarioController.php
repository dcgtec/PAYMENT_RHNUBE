<?php

namespace App\Http\Controllers\ApisExternas;

use App\AesCrypt;
use App\Http\Controllers\Controller;
use App\propietarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiPropietarioController extends Controller
{
    function listPropietarios()
    {
        try {
            $propietarios = Propietarios::orderBy('codigo', 'asc')->get();
            foreach ($propietarios as $propietario) {
                $propietario->password = AesCrypt::decrypt($propietario->password);
                $propietario->makeHidden('redes_sociales');
            }
            return response()->json($propietarios);
        } catch (Exception $e) {
            Log::error('Error en la función listPropietarios: ' . $e->getMessage());
            // Return a JSON response with the error
            return response()->json(['error' => 'Error listing propietarios'], 500);
        }
    }


    function accSaUpPropietario(Request $request)
    {
        try {

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error en la función accSaUpPropietario: ' . $e->getMessage());
            // Return a JSON response with the error
            return response()->json(['error' => 'Error listing accSaUpPropietario'], 500);
        }
    }
}
