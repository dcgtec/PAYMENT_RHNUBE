<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\ApisExternas\ApiAudotoriaController;
use App\Http\Controllers\ApisExternas\ApiCuponPaymentController;
use App\Http\Controllers\ApisExternas\ApiInvitacionController;
use App\Http\Controllers\ApisExternas\ApiPaqueteController;
use App\Http\Controllers\ApisExternas\ApiPeriodoController;
use App\Http\Controllers\ApisExternas\ApiPropietarioController;
use App\Http\Controllers\ApisExternas\ApiTransaccionController;
use App\Http\Controllers\PropietarioController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/generate-api-key', [ApiKeyController::class, 'generateKey']);

Route::middleware('api.key')->group(function () {
    /*PROPIETARIOS*/

    Route::get('/obtenerPropietarios', [ApiPropietarioController::class, 'listPropietarios']);
    Route::post('/accSaUpPropietario', [ApiPropietarioController::class, 'accSaUpPropietario']);

    /*CUPONES*/
    Route::get('/obtenerCupones', [ApiCuponPaymentController::class, 'listCupones']);
    Route::get('/obtenerCupon', [ApiCuponPaymentController::class, 'listCupon']);
    Route::get('/eliminarCupon', [ApiCuponPaymentController::class, 'deleteCupon']);
    Route::post('/actCupon', [ApiCuponPaymentController::class, 'actCupon']);
    Route::post('/accSaUpCupon', [ApiCuponPaymentController::class, 'accSaUpCupon']);

    /*PERIODO*/
    Route::get('/obtenerPeriodo', [ApiPeriodoController::class, 'listPeriodo']);

    /*PAQUETE*/
    Route::get('/obtenerPaquete', [ApiPaqueteController::class, 'listPaquete']);

    /*INVITACIONES*/
    Route::get('/obtenerInvitaciones', [ApiInvitacionController::class, 'listInvitaciones']);
    Route::get('/accionEstInvitacion', [ApiInvitacionController::class, 'changeEstInvitacion']);
    Route::post('/regUpdInvitacion', [ApiInvitacionController::class, 'regUpdInvitacion']);

    /*TRANSACCIONES*/
    Route::get('/compras-automatizacion', [ApiTransaccionController::class, 'mostrarCompraAuto']);
    Route::get('/mostrarcompras', [ApiTransaccionController::class, 'mostrarCompraAutoAjax']);
    Route::post('/accionTransaccion', [ApiTransaccionController::class, 'accionTransaccion']);

    Route::get('/mostrarTransacciones',  [ApiTransaccionController::class, 'mostrarTransacciones']);
    Route::post('/cambiarEstadoTransaccion', [ApiTransaccionController::class, 'cambiarEstadoTransaccion']);

    /*AUDOTORIA*/
    Route::GET('/obtenerAudotoria', [ApiAudotoriaController::class, 'listAudotira']);
    Route::post('/registerAudotira', [ApiAudotoriaController::class, 'registerAudotira']);
});
