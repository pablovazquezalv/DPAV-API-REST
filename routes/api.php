<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RazaController;
use App\Http\Controllers\PerroController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\SensoresController;
use App\Http\Controllers\CruceController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\CamadasController;
use App\Http\Controllers\TrackimoController;
use App\Http\Middleware\DisableCsrf;


//LOGIN
Route::post('/registrar', [UserController::class, 'registrarUsuario']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/loginSwarthWatch', [UserController::class, 'loginSwarthWatch']);
Route::post('/loginVerificarCodigoSmartWatch', [UserController::class, 'loginVerificarCodigoSmartWatch']);
Route::post('/verificarToken',[UserController::class, 'verificarToken'])->middleware('auth:sanctum');
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/olvideContraseña', [UserController::class, 'olvideContraseña']);
Route::post('restablecerContraseña', [UserController::class, 'restablecerContraseña'])->name('restablecerContraseña');

Route::get('/enviarSMS', [UserController::class, 'enviarSMS'])->name('enviarSMS');
Route::post('/verificarCodigo', [UserController::class, 'verificarCodigo'])->name('verificarCodigo');
Route::post('/enviarCodigoCuenta', [UserController::class, 'enviarCodigoCuenta'])->name('enviarCodigoCuenta');

//USUARIOS
Route::get('/mostrarUsuario', [UserController::class, 'mostrarUsuario'])->middleware('auth:sanctum');
Route::get('/mostrarMascotaPorUsuario', [PerroController::class, 'mostrarPerrosPorUsuario'])->middleware('auth:sanctum');
Route::post('/actualizarTelefono', [UserController::class, 'editarTelefonoUsuario'])->middleware('auth:sanctum');
Route::post('/verificarTelefono ', [UserController::class, 'verificarTelefono'])->middleware('auth:sanctum');
Route::put('/editarDireccion', [UserController::class, 'editarDireccionUsuario'])->middleware('auth:sanctum');
//Perros
Route::post('/crearMascota', [PerroController::class, 'crearPerro'])->middleware('auth:sanctum');
Route::get('/mostrarMascota/{id}', [PerroController::class, 'mostrarPerro']);
Route::get('/mostrarMascotas', [PerroController::class, 'mostrarPerros'])->middleware('auth:sanctum');
Route::get('/buscarPerros', [PerroController::class, 'buscarPerros']);
Route::get('/buscarPerras', [PerroController::class, 'buscarPerras']);
Route::put('/inhabilitarMascota/{id}', [PerroController::class, 'inhabilitarPerro'])->middleware('auth:sanctum');
Route::put('/habilitarMascota/{id}', [PerroController::class, 'habilitarPerro'])->middleware('auth:sanctum');
Route::post('/actualizarMascota/{id}', [PerroController::class, 'actualizarPerro'])->middleware('auth:sanctum');
Route::get('/mostrarMascotasEnVenta', [PerroController::class, 'mostrarPerrosEnVenta']);
Route::get('/mostrarMascotasEnVenta/{id}', [PerroController::class, 'mostrarPerrosEnVentaPorId']);
Route::get('/buscarMascotaPorChip/{id}', [PerroController::class, 'buscarPerroPorChip']);
Route::get('/mostrarMascotasRecientes', [PerroController::class, 'mostrarPerrosRecientes'])->middleware('auth:sanctum');
Route::post('/guardaMascota/{id?}', [PerroController::class, 'guardaPerro'])->middleware('auth:sanctum');

//Razas
Route::post('/crearRaza', [RazaController::class, 'crearRaza'])->middleware('auth:sanctum');
Route::get('/mostrarRaza/{id}', [RazaController::class, 'mostrarRaza']);
Route::get('/mostrarRazas', [RazaController::class, 'mostrarRazas']);
Route::get('/mostrarRazasInhabilitadas', [RazaController::class, 'mostrarRazasInhabilitadas']);
Route::get('/mostarRazasHabilitadas', [RazaController::class, 'mostrarRazasHabilitadas']);
Route::post('/actualizarRaza/{id}', [RazaController::class, 'actualizarRaza'])->middleware('auth:sanctum');
Route::put('/eliminarRaza/{id}', [RazaController::class, 'inahabilitarRaza']);
Route::put('/habilitarRaza/{id}', [RazaController::class, 'habilitarRaza']);
//Citas
Route::post('/crearCita', [CitaController::class, 'crearCita'])->middleware('auth:sanctum');
Route::put('/cancelarCita/{id}', [CitaController::class, 'cancelarCita'])->middleware('auth:sanctum');
//admin
Route::get('/mostrarCitasAdmin', [CitaController::class, 'mostrarCitas'])->middleware('auth:sanctum');
Route::post('/aceptarCitaAdmin/{id}', [CitaController::class, 'aceptarCitaAdmin'])->middleware('auth:sanctum');
Route::post('/cancelarCitaAdmin/{id}', [CitaController::class, 'cancelarCitaAdmin'])->middleware('auth:sanctum');
//usuarios
Route::get('/mostrarCitas', [CitaController::class, 'verMisCitas'])->middleware('auth:sanctum');
Route::get('/traerCitas', [CitaController::class, 'mostrarCitas'])->middleware('auth:sanctum');

//Cruces
Route::post('/crearCruce', [CruceController::class, 'createCruce'])->middleware('auth:sanctum');
Route::get('/mostrarCruces', [CruceController::class, 'showAllCruces'])->middleware('auth:sanctum');
Route::get('/mostrarCruce/{id}', [CruceController::class, 'showCruce'])->middleware('auth:sanctum');
Route::put('/actualizarCruce/{id}', [CruceController::class, 'updateCruce'])->middleware('auth:sanctum');


//Camadas
Route::post('/crearCamada', [CamadasController::class, 'createCamada'])->middleware('auth:sanctum');
Route::get('/mostrarCamadas', [CamadasController::class, 'mostrarCamadas'])->middleware('auth:sanctum');
//crearCachorro
Route::post('/crearCachorro', [CamadasController::class, 'crearCachorro'])->middleware('auth:sanctum');
Route::get('/mostrarCamada/{id}', [CamadasController::class, 'mostrarCamada'])->middleware('auth:sanctum');
Route::put('/actualizarCamada/{id}', [CamadasController::class, 'updateCamada'])->middleware('auth:sanctum');


Route::post('/sensores', [SensoresController::class, 'mandarDatosMongo']);
Route::post('/añadirSensor', [SensoresController::class, 'añadirSensor'])->middleware('auth:sanctum');
Route::get('/mostrarSensores', [SensoresController::class, 'obtenerSensores'])->middleware('auth:sanctum');
Route::get('/mostrarSensores/{id}', [SensoresController::class, 'show'])->middleware('auth:sanctum');
Route::get('/stream-sensor-data/{sensor_id}', [SensoresController::class, 'streamSensorData']);
Route::get('/stream-ubicacion/{deviceId}', [TrackimoController::class, 'streamUbicacion']);


Route::get('/oauth2/handler', [OAuthController::class, 'handleAuthorization']);
Route::get('/get-code', [OAuthController::class, 'getCode']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/crear/gps', [PerroController::class, 'crearGps']);
    Route::get('/mostrar/gps/{perro_id}', [PerroController::class, 'mostrarGps']);
    Route::put('/actualizar/gps/{id}', [PerroController::class, 'actualizarGps']);
    Route::post('/crear/zonas', [PerroController::class, 'crearZona']);
    Route::get('/mostrar/zona/{gps_id}', [PerroController::class, 'mostrarZonaGps']);
    Route::put('/actualizar/zona/{id}', [PerroController::class, 'actualizarZonaGps']);
});

//Trackimo
Route::middleware([DisableCsrf::class])->group(function () {
    Route::get('/login', [TrackimoController::class, 'login']);
    Route::post('/ultima-ubicacion', [TrackimoController::class, 'obtenerUltimaUbicacion']);
   
    Route::post('/loginGps', [TrackimoController::class, 'loginGps']);
    Route::get('/obtenerUbicacion/{device_id}', [TrackimoController::class, 'obtenerUbicacion']);
});

