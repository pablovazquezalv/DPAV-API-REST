<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerroController;
use App\Http\Controllers\RazaController;

Route::get('/', [PerroController::class, 'IndexPerrosView']);
Route::post('/crearPerro', [PerroController::class, 'crearPerro'])->name('crearPerro');

Route::get('/perro',[PerroController::class, 'CreatePerrosView']);
Route::get('/perros', [PerroController::class, 'mostrarPerros']);
Route::delete('/perros/{id}', [PerroController::class, 'eliminarPerro'])->name('perros.destroy');
Route::get('/perros/{id}', [PerroController::class, 'mostrarPerroView']);
Route::put('/perros/{id}/edit', [PerroController::class, 'actualizarPerro'])->name('perros.update');
Route::put('/perros/{id}', [PerroController::class, 'actualizarPerro'])->name('perros.actualizar');

Route::get('/razas', [RazaController::class, 'IndexRazasView']);
Route::post('/razas', [RazaController::class, 'crearRaza'])->name('crearRaza');
Route::put('/razas/{id}', [RazaController::class, 'actualizarRaza'])->name('razas.update');
//EditaRazasView
Route::get('/razas/{id}/edit', [RazaController::class, 'EditaRazasView']);
Route::delete('/razas/{id}', [RazaController::class, 'eliminarRaza'])->name('razas.destroy');
Route::get('/raza',[RazaController::class, 'CreateRazasView']);
Route::get('/razas/habilitadas', [RazaController::class, 'mostrarRazasHabilitadas']);
Route::get('/razas/inhabilitadas', [RazaController::class, 'mostrarRazasInhabilitadas']);
Route::get('/razas/{id}', [RazaController::class, 'mostrarRaza']);


