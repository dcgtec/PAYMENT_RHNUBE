<?php

namespace App\Http\Controllers\ApisExternas;

use App\Http\Controllers\Controller;
use App\InvitacionInfluencer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiInvitacionController extends Controller
{
    function listInvitaciones()
    {
        try {
            $invitaciones = InvitacionInfluencer::all();
            return response()->json($invitaciones);
        } catch (Exception $e) {
            Log::error('Error en la función listInvitaciones: ' . $e->getMessage());
            return response()->json(['error' => 'Error listInvitaciones.'], 500);
        }
    }

    function createUniqueToken()
    {
        return Str::random(40); // Genera un token único de 40 caracteres
    }

    function isTokenValid($token)
    {
        $invitacion = InvitacionInfluencer::where('token', $token)->first();

        if (!$invitacion) {
            return false; // Token no encontrado
        }

        $now = now();

        // Validar por fecha
        if ($now < $invitacion->fecha_inicio || $now > $invitacion->fecha_fin) {
            return false; // El token ha expirado por fecha
        }

        // Validar por cantidad
        if ($invitacion->cantidad_acumulada >= $invitacion->cantidad_total) {
            return false; // La cantidad acumulada supera la cantidad total
        }

        return true; // El token es válido
    }

    function changeEstInvitacion(Request $request)
    {
        try {

            $request->validate([
                'id_invitacion' => 'required|integer'
            ]);


            $invitacion = InvitacionInfluencer::find($request->id_invitacion);

            if (!$invitacion) {
                return response()->json(['success' => false, 'message' => 'Invitación no encontrada.'], 404);
            }

            $invitacion->estado = ($invitacion->estado === 1) ? 0 : 1;

            // Guardar los cambios
            $invitacion->save();

            return response()->json(['success' => true, 'message' => 'Invitación actualizada exitosamente.'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors(), // Esto te dirá qué campos fallaron
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    function regUpdInvitacion(Request $request)
    {
        try {
            $request->validate([
                'nombres' => 'required',
                'accion' => 'required|in:guardar,editar',
                'fechIni' => 'required',
                'fechFin' => 'required',
                'cant' => 'required',
            ]);

            if ($request->accion === 'guardar') {

                $token = $this->createUniqueToken();
                $invitacion = new InvitacionInfluencer();
                $invitacion->nombre_invitacion = $request->nombres;
                $invitacion->fecha_inicio = $request->fechIni;
                $invitacion->fecha_fin = $request->fechFin;
                $invitacion->cantidad_total = $request->cant;
                $invitacion->token = $token;
                $invitacion->fecha_inicio =  $request->fechIni;
                $invitacion->fecha_fin =  $request->fechFin;
                $invitacion->fecha_fin =  $request->fechFin;
                $link = 'https://payment.rhnube.com.pe/pl?cid=' . $token;
                $invitacion->link =   $link;
                $invitacion->save();

                return response()->json(['success' => true, 'message' => 'Invitación actualizada exitosamente.'], 200);
            } elseif ($request->accion === 'editar') {

                // Buscar la invitación existente
                $invitacion = InvitacionInfluencer::find($request->id_invitacion);

                if (!$invitacion) {
                    return response()->json(['success' => false, 'message' => 'Invitación no encontrada.'], 404);
                }

                // Actualizar la invitación
                $invitacion->nombre_invitacion = $request->nombres;
                $invitacion->fecha_inicio = $request->fechIni;
                $invitacion->fecha_fin = $request->fechFin;
                $invitacion->cantidad_total = $request->cant;

                // Guardar los cambios
                $invitacion->save();

                return response()->json(['success' => true, 'message' => 'Invitación actualizada exitosamente.'], 200);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors(), // Esto te dirá qué campos fallaron
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
