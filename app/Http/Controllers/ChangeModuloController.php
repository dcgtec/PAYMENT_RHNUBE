<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ChangeModuloController extends Controller
{

    public function showPassword(Request $request)
    {
        $ce = $request->query('ce');

        if (!$ce) {
            // Si 'ce' no está presente, devolver una respuesta de error o redirigir
            abort(404);
        }

        $response = Http::post('https://rhnube.com.pe/api/validateStatusToken', [
            'token' => $ce,
            'type_token' => 'pass-change'
        ]);

        $respuesta = $response->json();

        if ($respuesta["success"]) {
            session()->forget('logeado');
            session()->forget('detalleUusario');
            return view('influencers.changePassword', compact('ce'));
        }

        abort(404);
    }

    public function showEmail(Request $request)
    {
        $ce = $request->query('ce');

        if (!$ce) {
            // Si 'ce' no está presente, devolver una respuesta de error o redirigir
            abort(404);
        }

        $response = Http::post('https://rhnube.com.pe/api/validateStatusToken', [
            'token' => $ce,
            'type_token' => 'email-change'
        ]);

        $respuesta = $response->json();

        if ($respuesta["success"]) {
            session()->forget('logeado');
            session()->forget('detalleUusario');
            return view('influencers.changeEmail', compact('ce'));
        }

        abort(404);
    }

    public function changePasswords(Request $request)
    {
        try {

            $email = $request->input('email');
            $token = $request->input('token');
            $password = $request->input('password');
            $newPassword = $request->input('newpassword');
            $codValidation = $request->input('codValidation');

            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'newpassword' => 'required|same:password',
                'codValidation' => 'required|digits:12',
            ]);

            // Si la validación falla, lanzar una excepción con los errores
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $response = Http::post('https://rhnube.com.pe/api/updatePasswprdToken', [
                'email' => $email,
                'token' => $token,
                'password' => $password,
                'confirm_password' => $newPassword,
                'codigo_validacion' => $codValidation,
            ]);

            $data = $response->json();

            $respuesta = $data["success"];
            $mensaje = $data["message"];

            return response()->json([
                'success' => $respuesta,
                'message' => $mensaje,
            ], 201);


            // Resto del código...

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

            $email = $request->input('email');
            $token = $request->input('token');
            $codValidation = $request->input('codValidation');

            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'codValidation' => 'required|digits:12',
            ]);

            // Si la validación falla, lanzar una excepción con los errores
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $response = Http::post('https://rhnube.com.pe/api/updateCorreoToken', [
                'new_email' => $email,
                'token' => $token,
                'codigo_validacion' => $codValidation,
            ]);

            $data = $response->json();

            $respuesta = $data["success"];
            $mensaje = $data["message"];

            return response()->json([
                'success' => $respuesta,
                'message' => $mensaje,
            ], 201);


            // Resto del código...

        } catch (ValidationException $e) {
            // Capturar los mensajes de error de validación y devolverlos como respuesta
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Capturar cualquier otra excepción y devolver un mensaje de error genérico
            return response()->json(['message' => 'Ha ocurrido un error'], 500);
        }
    }
}
