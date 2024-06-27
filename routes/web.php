<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/restablecerContraseña',[UserController::class, 'restablecerContraseñaView'])->name('restablecerContraseñaView');

Route::get('/contraseñaRestablecida',[UserController::class, 'contraseñaRestablecidaView'])->name('contraseñaRestablecidaView');