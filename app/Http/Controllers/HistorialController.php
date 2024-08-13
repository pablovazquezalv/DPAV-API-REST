<?php

namespace App\Http\Controllers;

use App\Models\Gps;
use Illuminate\Http\Request;
use App\Models\Historial;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class HistorialController extends Controller
{

    public function registrarAlerta(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitud' => 'required',
            'longitud' => 'required',
            // 'fecha' => 'required',
            // 'hora' => 'required',
             'gps_id' => 'required|exists:gps,id'
        ],
            [
                'latitud.required' => 'La latitud es requerida',
                'longitud.required' => 'La longitud es requerida',
                // 'fecha.required' => 'La fecha es requerida',
                // 'hora.required' => 'La hora es requerida',
                'gps_id.required' => 'El gps_id es requerido',
                'gps_id.exists' => 'El gps_id no existe'
            ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al registrar la alerta',
                'errors' => $validator->errors()
            ], 400);
        }

        $response = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng='
            . $request->latitud . ',' . $request->longitud . '&key=AIzaSyCUl6BBdSHA0A-MWtcPRj0KTQtAIBtavb8')
            ->json();
        
        
        $direccion = $response['results'][0]['formatted_address'];

        $historial = Historial::create([
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'direccion' => $direccion,
            'link' => 'https://www.google.com/maps/search/?api=1&query=' . $request->latitud . ',' . $request->longitud,
            //LINK DE DE UBICACION PARA MOSTRAR EN EL MAPA
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
            'gps_id' => $request->gps_id      
        ]);


        $historial->save();

        
        if ($historial) {
            return response()->json([
                'message' => 'Alerta registrada correctamente',
                'historial' => $historial
            ], 201, [], JSON_UNESCAPED_SLASHES);
        } else {
            return response()->json([
                'message' => 'Error al registrar la alerta'
            ], 400);
        }

    }

    public function mostrarAlertas(Request $request,$id)
    {

        $user = $request->user();

       
        $historial = Historial::Select
        (
            'historial.id',
            'historial.latitud',
            'historial.longitud',
            'historial.direccion',
            'historial.fecha',
            'historial.hora',
            'historial.gps_id'
        )->join('gps', 'historial.gps_id', '=', 'gps.id')
        ->join('perros', 'gps.perro_id', '=', 'perros.id')
        ->where('perros.user_id', '=', $user->id)
        ->where('gps.id', '=', $id)
        ->get();

        
        if($historial->isEmpty()){
            return response()->json([
                'message' => 'No se encontraron alertas'
            ], 404);
        }

        return response()->json([
            'message' => 'Alertas encontradas',
            'historial' => $historial
        ], 200,[], JSON_UNESCAPED_SLASHES);
    }

    public function getLink()
    {

        $latitud = 25.59799433;
        $longitud = -103.41291189;

        $response = Http::post('https://maps.googleapis.com/maps/api/geocode/json?latlng='
            . $latitud . ',' . $longitud . '&key=AIzaSyCUl6BBdSHA0A-MWtcPRj0KTQtAIBtavb8')
            ->json();

            return response()->json([
                'message' => 'Link generado',
                'address' => $response['results'][0]['formatted_address'],
                'link' => 'https://www.google.com/maps/search/?api=1&query=' . $latitud . ',' . $longitud
            ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
