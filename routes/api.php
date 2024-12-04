<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\PerroController;



Route::post('/registrar', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/olvideContraseña', [UserController::class, 'olvideContraseña']);
Route::post('restablecerContraseña', [UserController::class, 'restablecerContraseña'])->name('restablecerContraseña');

Route::get('/enviarSMS', [UserController::class, 'enviarSMS'])->name('enviarSMS');
Route::post('/verificarCodigo', [UserController::class, 'verificarCodigo'])->name('verificarCodigo');


Route::post('/crearPerro', [PerroController::class, 'crearPerro'])->name('crearPerro');
Route::get('/perros/{id}/edit', [PerroController::class, 'mostrarPerro']);
Route::get('/mostrarPerros', [PerroController::class, 'mostrarPerros']);
Route::put('inhabilitarPerro/{id}', [PerroController::class, 'inhabilitarPerro'])->middleware('auth:sanctum');
Route::put('habilitarPerro/{id}', [PerroController::class, 'habilitarPerro'])->middleware('auth:sanctum');
Route::put('/perros/{id}/edit', [PerroController::class, 'actualizarPerro']);
Route::delete('/perros/{id}', [PerroController::class, 'eliminarPerro'])->name('perros.destroy');
//Razas
Route::post('/crearRaza', [RazaController::class, 'crearRaza']);

Route::get('/mostrarRazasInhabilitadas', [RazaController::class, 'mostrarRazasInhabilitadas']);
Route::get('mostarRazasHabilitadas', [RazaController::class, 'mostrarRazasHabilitadas']);
Route::put('/actualizarRaza/{id}', [RazaController::class, 'actualizarRaza']);
Route::put('/eliminarRaza/{id}', [RazaController::class, 'inahabilitarRaza']);
Route::get('/razas/{id}', [RazaController::class, 'mostrarRaza']);
Route::get('/razas', [RazaController::class, 'mostrarRazas']);






