<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



Route::post('/registrar', [UserController::class, 'registrarUsuario']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/olvideContraseña', [UserController::class, 'olvideContraseña']);
Route::post('restablecerContraseña', [UserController::class, 'restablecerContraseña'])->name('restablecerContraseña');

Route::get('/enviarSMS', [UserController::class, 'enviarSMS'])->name('enviarSMS');
Route::post('/verificarCodigo', [UserController::class, 'verificarCodigo'])->name('verificarCodigo');

