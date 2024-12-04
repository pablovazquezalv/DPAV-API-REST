<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerroController;

Route::get('/', [PerroController::class, 'IndexPerrosView']);



//vista crear perro
Route::get('/perro',[PerroController::class, 'CreatePerrosView']);
Route::delete('/perros/{id}', [PerroController::class, 'eliminarPerro'])->name('perros.destroy');
Route::get('/perros/{id}', [PerroController::class, 'mostrarPerroView']);
//vista editar perro
Route::put('/perros/{id}/edit', [PerroController::class, 'actualizarPerro'])->name('perros.update');
