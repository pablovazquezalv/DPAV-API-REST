<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerroController;
use App\Http\Controllers\RazaController;

Route::get('/', [PerroController::class, 'IndexPerrosView']);
Route::get('/perro',[PerroController::class, 'CreatePerrosView']);
Route::delete('/perros/{id}', [PerroController::class, 'eliminarPerro'])->name('perros.destroy');
Route::get('/perros/{id}', [PerroController::class, 'mostrarPerroView']);
Route::put('/perros/{id}/edit', [PerroController::class, 'actualizarPerro'])->name('perros.update');

Route::get('/razas', [RazaController::class, 'IndexRazasView']);
Route::delete('/razas/{id}', [RazaController::class, 'eliminarRaza'])->name('razas.destroy');
Route::get('/raza',[RazaController::class, 'CreateRazasView']);
