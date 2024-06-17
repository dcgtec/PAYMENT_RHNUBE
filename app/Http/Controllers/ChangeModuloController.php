<?php

namespace App\Http\Controllers;

use App\AesCrypt;
use App\change_tokens;
use App\propietarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ChangeModuloController extends Controller
{

    public function showPassword(Request $request)
    {
        return $this->showChangeForm($request, 'pass-change', 'influencers.changePassword');
    }

    public function showEmail(Request $request)
    {
        return $this->showChangeForm($request, 'email-change', 'influencers.changeEmail');
    }

    private function showChangeForm(Request $request, $typeToken, $view)
    {
        $ce = $request->query('ce');
        if (!$ce) {
            abort(404);
        }

        $changeToken = change_tokens::where('token', $ce)
            ->where('type_token', $typeToken)
            ->where('expires_at', '>', now())
            ->first();

        if (!$changeToken) {
            abort(404);
        }

        session()->forget('logeado');
        session()->forget('detalleUusario');
        return view($view, compact('ce'));
    }

    public function changePasswords(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'token' => 'required',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*#?&]/'
                ],
                'newpassword' => 'required|same:password',
                'email' => 'required|email',
                'codValidation' => 'required|digits:12'
            ]);

            $token = $validatedData['token'];
            $password = $validatedData['password'];
            $newPassword = $validatedData['newpassword'];
            $email = $validatedData['email'];
            $codValidation = $validatedData['codValidation'];

            $changeToken = change_tokens::where('token',  $token)
                ->where('type_token', 'pass-change')
                ->where('codigo_validacion', $codValidation)
                ->first();

            if (!$changeToken || $changeToken->expires_at < Carbon::now()) {
                return response()->json(['success' => false, 'message' => 'El token o el código de validación es inválido o ha expirado.'], 200);
            }

            $propietario = propietarios::where('correo', $request->email)->first();

            if (!$propietario) {
                return response()->json(['success' => false, 'message' => 'No se encontró ningún propietario con el correo electrónico proporcionado.'], 200);
            }

            $propietario->password = AesCrypt::encrypt($password);
            $propietario->save();
            $changeToken->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada con éxito.',
            ], 200);

        } catch (ValidationException $e) {
            // Capturar los mensajes de error de validación y devolverlos como respuesta
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Capturar cualquier otra excepción y devolver un mensaje de error genérico
            return response()->json(['message' => 'Ha ocurrido un error'], 500);
        }
    }


    public function changeEmail(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'new_email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        // Verificar si el nuevo correo electrónico ya existe en la base de datos
                        $existingPropietario = propietarios::where('correo', $value)->exists();
                        if ($existingPropietario) {
                            $fail('El correo electrónico ya está en uso.');
                        }
                    }
                ],
                'codValidation' => 'required|size:12'
            ]);

            $changeToken = change_tokens::where('token', $request->token)
                ->where('type_token', 'email-change')
                ->where('codigo_validacion', $request->codValidation)
                ->first();

            if (!$changeToken || $changeToken->expires_at < Carbon::now()) {
                return response()->json(['success' => false, 'message' => 'El token o el código de validación es inválido o ha expirado.'], 400);
            }

            $propietario = propietarios::find($changeToken->id_propietario);
            $propietario->correo = $request->new_email;
            $propietario->save();
            $changeToken->delete();

            return response()->json([
                'success' => true,
                'message' => 'Correo electrónico actualizado con éxito.',
            ], 200);
        } catch (ValidationException $e) {
            // Capturar los mensajes de error de validación y devolverlos como respuesta
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Capturar cualquier otra excepción y devolver un mensaje de error genérico
            return response()->json(['message' => 'Ha ocurrido un error'], 500);
        }
    }
}
