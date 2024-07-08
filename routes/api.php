<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\PerroController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CitaController;

//LOGIN
Route::post('/registrar', [UserController::class, 'registrarUsuario']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/olvideContraseña', [UserController::class, 'olvideContraseña']);
Route::post('restablecerContraseña', [UserController::class, 'restablecerContraseña'])->name('restablecerContraseña');

Route::get('/enviarSMS', [UserController::class, 'enviarSMS'])->name('enviarSMS');
Route::post('/verificarCodigo', [UserController::class, 'verificarCodigo'])->name('verificarCodigo');
Route::post('/enviarCodigoCuenta', [UserController::class, 'enviarCodigoCuenta'])->name('enviarCodigoCuenta');

//USUARIOS
Route::get('/mostrarUsuario', [UserController::class, 'mostrarUsuario'])->middleware('auth:sanctum');
Route::get('/mostrarPerroPorUsuario', [PerroController::class, 'mostrarPerrosPorUsuario'])->middleware('auth:sanctum');
Route::post('/actualizarTelefono', [UserController::class, 'editarTelefonoUsuario'])->middleware('auth:sanctum');
Route::post('/verificarTelefono ', [UserController::class, 'verificarTelefono'])->middleware('auth:sanctum');
//Perros
Route::post('/crearPerro', [PerroController::class, 'crearPerro'])->middleware('auth:sanctum');
Route::get('/mostrarPerro/{id}', [PerroController::class, 'mostrarPerro']);
Route::get('/mostrarPerros', [PerroController::class, 'mostrarPerros'])->middleware('auth:sanctum');
Route::get('/buscarPerros', [PerroController::class, 'buscarPerros']);
Route::get('/buscarPerras', [PerroController::class, 'buscarPerras']);
Route::put('/inhabilitarPerro/{id}', [PerroController::class, 'inhabilitarPerro'])->middleware('auth:sanctum');
Route::put('/habilitarPerro/{id}', [PerroController::class, 'habilitarPerro'])->middleware('auth:sanctum');
Route::post('/actualizarPerro/{id}', [PerroController::class, 'actualizarPerro'])->middleware('auth:sanctum');
Route::get('/mostrarPerrosEnVenta', [PerroController::class, 'mostrarPerrosEnVenta']);
Route::get('/mostrarPerrosEnVenta/{id}', [PerroController::class, 'mostrarPerrosEnVentaPorId']);
Route::get('/buscarPerroPorChip/{id}', [PerroController::class, 'buscarPerroPorChip']);
Route::get('/mostrarPerrosRecientes', [PerroController::class, 'mostrarPerrosRecientes'])->middleware('auth:sanctum');
//Razas
Route::post('/crearRaza', [RazaController::class, 'crearRaza'])->middleware('auth:sanctum');
Route::get('/mostrarRaza/{id}', [RazaController::class, 'mostrarRaza']);
Route::get('/mostrarRazas', [RazaController::class, 'mostrarRazas']);
Route::get('/mostrarRazasInhabilitadas', [RazaController::class, 'mostrarRazasInhabilitadas']);
Route::get('/mostarRazasHabilitadas', [RazaController::class, 'mostrarRazasHabilitadas']);
Route::post('/actualizarRaza/{id}', [RazaController::class, 'actualizarRaza'])->middleware('auth:sanctum');
Route::put('/eliminarRaza/{id}', [RazaController::class, 'inahabilitarRaza']);
Route::put('/habilitarRaza/{id}', [RazaController::class, 'habilitarRaza']);
//Certificados
Route::post('crearCertificado', [CertificadoController::class, 'crearCertificado']);
//Citas
Route::post('/crearCita', [CitaController::class, 'crearCita'])->middleware('auth:sanctum');
Route::put('/cancelarCita/{id}', [CitaController::class, 'cancelarCita'])->middleware('auth:sanctum');
//admin
Route::get('/mostrarCitasAdmin', [CitaController::class, 'mostrarCitas'])->middleware('auth:sanctum');
//usuarios
Route::get('/mostrarCitas', [CitaController::class, 'verMisCitas'])->middleware('auth:sanctum');
Route::get('/traerCitas', [CitaController::class, 'mostrarCitas'])->middleware('auth:sanctum');

