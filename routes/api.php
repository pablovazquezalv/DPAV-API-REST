<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\PerroController;





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
Route::delete('/razas/{id}', [RazaController::class, 'eliminarRaza']);
Route::get('/razas/{id}', [RazaController::class, 'mostrarRaza']);
Route::get('/razas', [RazaController::class, 'mostrarRazas']);






