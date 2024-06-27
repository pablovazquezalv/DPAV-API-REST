<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\PerroController;
use App\Http\Controllers\CertificadoController;



Route::post('/registrar', [UserController::class, 'registrarUsuario']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/olvideContraseña', [UserController::class, 'olvideContraseña']);
Route::post('restablecerContraseña', [UserController::class, 'restablecerContraseña'])->name('restablecerContraseña');

Route::get('/enviarSMS', [UserController::class, 'enviarSMS'])->name('enviarSMS');
Route::post('/verificarCodigo', [UserController::class, 'verificarCodigo'])->name('verificarCodigo');
Route::post('/enviarCodigoCuenta', [UserController::class, 'enviarCodigoCuenta'])->name('enviarCodigoCuenta');

//Perros
Route::post('/crearPerro', [PerroController::class, 'crearPerro']);#->middleware('auth:sanctum');





Route::get('/mostrarperro/{id}', [PerroController::class, 'mostrarPerro'])->middleware('auth:sanctum');
Route::get('/mostrarPerros', [PerroController::class, 'mostrarPerros'])->middleware('auth:sanctum');
Route::put('/inhabilitarPerro/{id}', [PerroController::class, 'inhabilitarPerro'])->middleware('auth:sanctum');
Route::put('habilitarPerro/{id}', [PerroController::class, 'habilitarPerro'])->middleware('auth:sanctum');
Route::put('actualizarPerro/{id}', [PerroController::class, 'actualizarPerro']);
Route::get('mostrarPerrosEnVenta', [PerroController::class, 'mostrarPerrosEnVenta']);
//Razas
Route::post('/crearRaza', [RazaController::class, 'crearRaza']);
Route::get('/mostrarRaza/{id}', [RazaController::class, 'mostrarRaza']);
Route::get('/mostrarRazas', [RazaController::class, 'mostrarRazas']);
Route::get('/mostrarRazasInhabilitadas', [RazaController::class, 'mostrarRazasInhabilitadas']);
Route::get('/mostarRazasHabilitadas', [RazaController::class, 'mostrarRazasHabilitadas']);
Route::put('/actualizarRaza/{id}', [RazaController::class, 'actualizarRaza']);
Route::put('/eliminarRaza/{id}', [RazaController::class, 'inahabilitarRaza']);
Route::put('/habilitarRaza/{id}', [RazaController::class, 'habilitarRaza']);
//Certificados
Route::post('crearCertificado', [CertificadoController::class, 'crearCertificado']);