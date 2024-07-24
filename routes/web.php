<?php

use App\Http\Controllers\Automatizacion\AutomatizacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CategoryPlanController;
use App\Http\Controllers\ChangeModuloController;
use App\Http\Controllers\InfluencerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropietarioController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('plans');
});

Auth::routes();

Route::get('/', [CategoryPlanController::class, 'index']);
Route::get('payment/{slug?}', [CategoryPlanController::class, 'show'])->name('category_plans.show')->where('slug', '(.*)');
Route::get('update_prices', [PlanController::class, 'updatePrices'])->name('update_prices');

// Ruta para el proceso de pago (checkout)
// Rutas en web.php (o donde defines tus rutas)
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
Route::get('/obtenerCupon', [PaymentController::class, 'obtenerCupon']);
Route::get('/obtenerPlan', [PaymentController::class, 'obtenerPlan']);
Route::get('/success', [PaymentController::class, 'success'])->name('success');
Route::get('/error', [PaymentController::class, 'error'])->name('error');
Route::post('/reenviar-correo', [PaymentController::class, 'reenviarCorreo']);
Route::get('/failure', [PaymentController::class, 'failure'])->name('failure');
Route::get('/pending', [PaymentController::class, 'pending'])->name('pending');

Route::post('/', [PaymentController::class, 'index'])->name('index');


//Influencer
Route::get('/iniciarSesion', [InfluencerController::class, 'index']);
Route::post('/logear', [InfluencerController::class, 'login']);
// Ruta para el enlace con 'pl' y 'cid'
Route::get('/pl', [InfluencerController::class, 'show'])->name('pl.show');
Route::post('/registrarNuevo', [InfluencerController::class, 'registrarNuevo']);

Route::middleware(['AuthSession'])->group(function () {
    Route::get('/perfil', [InfluencerController::class, 'perfil']);
    Route::get('/referidos', [InfluencerController::class, 'referidos']);
    Route::get('/micupon', [InfluencerController::class, 'micupon']);
    Route::get('/retiros', [InfluencerController::class, 'retiros']);
    Route::get('/logout', [InfluencerController::class, 'logout']);
    Route::get('/actualizarPerfil', [InfluencerController::class, 'actualizarPerfil']);
    Route::post('/upload-image', [InfluencerController::class, 'uploadImage']);
    Route::get('/deletePthoPerfil', [InfluencerController::class, 'deletePthoPerfil']);
    Route::get('/cambiarEstadoPorCobrar', [InfluencerController::class, 'cambiarEstadoPorCobrar']);
    Route::post('/retirarDinero', [InfluencerController::class, 'retirarDinero']);
    Route::post('/eliminarRetiro', [InfluencerController::class, 'cancelarRetiro']);
});


/*ENVIO DE CORREOS PARA SOLICITAR CAMBIO DE CORREO Y CONTRASEÑAS*/
Route::get('/cambiarEmail', [PropietarioController::class, 'sendChangeEmailPasswordToken']);
/**===================================*/
Route::get('/pass-change', [ChangeModuloController::class, 'showPassword']);
Route::get('/email-change', [ChangeModuloController::class, 'showEmail']);
Route::post('/changePasswords', [ChangeModuloController::class, 'changePasswords']);
Route::post('/changeEmail', [ChangeModuloController::class, 'changeEmail']);

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);


Route::get('/enviarEjemplo', [ChangeModuloController::class, 'enviarEjemplo']);
