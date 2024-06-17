<?php

namespace App\Http\Controllers\ApisExternas;

use App\cupon;
use App\Http\Controllers\Controller;
use App\OperacionTransferencia;
use App\PaymentUsuario;
use App\propietarios;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiTransaccionController extends Controller
{
    function mostrarCompraAuto()
    {
        try {
            $paymentUsuarios = PaymentUsuario::orderBy('fecha_compra', 'desc')->get(); // Ordenar por fecha de compra de forma descendente
            // Array asociativo para almacenar el propietario correspondiente a cada pago
            $codCupnArray = [];
            $codOperacion = [];

            if (count($paymentUsuarios) > 0) {

                // Decodificar el JSON y obtener el valor de codCupn
                foreach ($paymentUsuarios as $payment) {
                    $dato_usuario = json_decode($payment->dato_usuario, true);
                    $codCupn = $dato_usuario['codCupn'];
                    $cupon = cupon::where('name_cupon', $codCupn)->first();
                    $propietario = propietarios::select('id_propietario', 'codigo', 'nombres', 'apellido_paterno', 'apellido_materno', 'razon_social', 'correo',  'cargo', 'banco', 'tipo_de_cuenta', 'cci', 'numero_de_cuenta')
                        ->where('id_propietario', $cupon->id_propietario)
                        ->first();

                    $operaciones_transferencias = OperacionTransferencia::select('numero_operacion')
                        ->where('id_detalle_payment_compra', $payment->id)
                        ->first();

                    // Asociar el propietario con el pago correspondiente
                    $codCupnArray[$payment->id] = $propietario;
                    $codOperacion[$payment->id] = $operaciones_transferencias;
                }
            }

            return response()->json($paymentUsuarios);
        } catch (Exception $e) {
            Log::error('Error en la función mostrarCompraAuto: ' . $e->getMessage());
            // Return a JSON response with the error
            return response()->json(['error' => 'Error mostrarCompraAuto'], 500);
        }
    }

    function mostrarCompraAutoAjax()
    {
        try {
            $paymentUsuarios = PaymentUsuario::orderBy('fecha_compra', 'desc')->get();
            $codCupnArray = [];
            $codOperacion = [];

            if (count($paymentUsuarios)) {
                // Decodificar el JSON y obtener el valor de codCupn
                foreach ($paymentUsuarios as $payment) {
                    $dato_usuario = json_decode($payment->dato_usuario, true);
                    $codCupn = $dato_usuario['codCupn'];
                    $cupon = Cupon::where('name_cupon', $codCupn)->first();

                    // Verificar si se encontró el cupón
                    if ($cupon) {
                        $propietario = propietarios::select('id_propietario', 'codigo', 'nombres', 'apellido_paterno', 'apellido_materno', 'razon_social', 'correo',  'cargo', 'banco', 'tipo_de_cuenta', 'cci', 'numero_de_cuenta')
                            ->where('id_propietario', $cupon->id_propietario)
                            ->first();

                        $operaciones_transferencias = OperacionTransferencia::select('numero_operacion')
                            ->where('id_detalle_payment_compra', $payment->id)
                            ->first();

                        // Asociar el propietario con el pago correspondiente
                        $codCupnArray[$payment->id] = $propietario;
                        $codOperacion[$payment->id] = $operaciones_transferencias;
                    }
                }
            }

            // Pasar el array de codCupn al view
            return response()->json(['paymentUsuarios' => $paymentUsuarios, 'codCupnArray' => $codCupnArray, 'codOperacion' => $codOperacion]);
        } catch (Exception $e) {
            Log::error('Error en la función mostrarCompraAutoAjax: ' . $e->getMessage());
            // Return a JSON response with the error
            return response()->json(['error' => 'Error mostrarCompraAutoAjax'], 500);
        }
    }

    function accionTransaccion(Request $request)
    {
        try {

            $request->validate([
                'estTra' => 'required|integer|between:0,4',
                'codCompra' => 'required',
                'propietario' => 'required',
                'nOperacion' => 'nullable',
                'comentario' => 'nullable',
                'changed_by' => 'nullable'
            ]);

            $estTra = $request->input('estTra');
            $codCompra = $request->input('codCompra');
            $propietario = $request->input('propietario');
            $nOperacion = $request->input('nOperacion');
            $comentario = $request->input('comentario');
            $changed_by = $request->input('changed_by');

            $codigoExistente = PaymentUsuario::where('codigo_compra', $codCompra)->first();

            if (!$codigoExistente) {
                return response()->json(['success' => false, 'message' => 'El código de compra no existe'], 200);
            }

            $codigoExistente->estado_transacion = $estTra;
            $codigoExistente->comentario = $comentario;

            // Llamar al método store del controlador de auditoría para registrar la acción
            $auditLogController = new ApiAudotoriaController();

            // Datos para la auditoría
            $auditData = [
                'table_name' => 'payment_usuarios', // Nombre de la tabla donde se realizó la acción
                'operation' => 'UPDATE', // Operación realizada (en este caso, una actualización)
                'primary_key_value' => $codigoExistente->id, // Valor de la clave primaria afectada
                'changed_data' => json_encode($request->all()), // Datos modificados
                'changed_by' => $changed_by,
                //'changed_by' => Auth::id(), // Usuario que realizó la acción
            ];

            // Llamar al método store del controlador de auditoría para almacenar el registro
            $auditLogController->registerAudotira(new Request($auditData));

            if ($estTra == 4) {
                $codigoExistente->save();
                OperacionTransferencia::create([
                    "id_detalle_payment_compra" => $codigoExistente->id,
                    "id_propietario" => $propietario,
                    "numero_operacion" => $nOperacion,
                ]);
            } elseif ($codigoExistente->save()) {
                return response()->json(['success' => true, 'message' => 'Transacción guardada exitosamente']);
            } else {
                return response()->json(['success' => false, 'message' => 'Error al guardar el propietario']);
            }

            return response()->json(['success' => true, 'message' => 'Transacción guardada exitosamente']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error en la función accionTransaccion: ' . $e->getMessage());
            // Return a JSON response with the error
            return response()->json(['error' => 'Error listing accionTransaccion'], 500);
        }
    }
}