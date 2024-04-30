<?php

use App\Http\Controllers\Automatizacion\AutomatizacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CategoryPlanController;
use App\Http\Controllers\InfluencerController;
use App\Http\Controllers\PaymentController;
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

// Route::middleware("auth")->group(function () {

// Route::get('payment/{slug}', [CategoryPlanController::class, 'show'])->name('category_plans.show');
Route::get('payment/{slug?}', [CategoryPlanController::class, 'show'])->name('category_plans.show')->where('slug', '(.*)');

// routes/web.php
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

Route::middleware(['AuthSession'])->group(function () {
    Route::get('/perfil', [InfluencerController::class, 'perfil']);
    Route::get('/referidos', [InfluencerController::class, 'referidos']);
    Route::get('/retiros', [InfluencerController::class, 'retiros']);
    Route::post('/actualizarPerfil', [InfluencerController::class, 'actualizarPerfil']);
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
