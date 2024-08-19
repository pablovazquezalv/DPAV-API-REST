<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use MongoDB\Client as MongoClient;
use App\Models\Sensor;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;


class SensoresController extends Controller
{
    protected $client;
    protected $database;

    public function __construct()
    {
        // Conectar a MongoDB usando la URI de conexión
        $this->client = new MongoClient(env('MONGO_DB_URI'));
        $this->database = $this->client->selectDatabase(env('MONGO_DB_DATABASE'));
    }

    //ANADIR SENSOR EN BD Y EN MONGO
    public function añadirSensor(Request $request)
    {
        // Validar datos
        $validator = Validator::make(
            $request->all(),
            [
                'sensor_id' => 'required|string',
                'ubicacion' => 'required|string',
            ],
            [
                'sensor_id.required' => 'El campo nombre es obligatorio',
                'sensor_id.string' => 'El campo nombre debe ser una cadena de texto',
                'ubicacion.required' => 'El campo ubicacion es obligatorio',
                'ubicacion.string' => 'El campo ubicacion debe ser una cadena de texto',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Crear un nuevo sensor
        $sensor = Sensor::create([
            'sensor_id' => $request->input('sensor_id'),
            'ubicacion' => $request->input('ubicacion'),
        ]);

        if ($sensor->save()) {
            // Crear una colección en MongoDB específica para el sensor
            $collectionName =  $request->input('sensor_id');
            $this->database->createCollection($collectionName);

            return response()->json(['message' => 'Sensor creado y colección en MongoDB creada', 'sensor' => $sensor], 201);
        } else {
            return response()->json(['message' => 'Error al crear el sensor'], 500);
        }
    }

    //MANDAR DATOS A MONGO

    public function mandarDatosMongo(Request $request)
    {
        // Validar datos
        $validator = Validator::make(
            $request->all(),
            [
                'sensor_id' => 'required|string',
                'value' => 'required|numeric',
                'nivel' => ['required', Rule::in(['bajo', 'medio', 'lleno', 'vacio'])],
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Determinar el nombre de la colección basado en el sensor_id
        $sensorId = $request->input('sensor_id');
        $collectionName = $sensorId; // Nombre de la colección por sensor
        $collection = $this->database->selectCollection($collectionName);
    


        $sensor = Sensor::where('sensor_id', $sensorId)->first();

        
        // Insertar datos en la colección específica del sensor
        $result = $collection->insertOne([
            'sensor_id' => $sensorId,
            'value' => $request->input('value'),
            'nivel' => $request->input('nivel'),
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i'),
        ]);
    

        // Si el nivel es vacio, enviar alerta
        if ($request->input('nivel') == 'vacio') 
        {
            $cacheKey = 'last_notification_' . $sensorId;
    
            // Obtener la última vez que se envió una notificación
            $lastNotificationTime = Cache::get($cacheKey);

    
            if (!$lastNotificationTime || now()->diffInMinutes($lastNotificationTime) >= 4)
             {
                // Enviar la notificación aquí
                $response = Http::withHeaders([
                    'Authorization' => 'App ae6f077ec349231e89761c8c54350ab6-e558221a-0ade-4c70-a4c1-cbb04196ef64',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->post('https://e1qzvq.api.infobip.com/sms/2/text/advanced', [
                    'messages' => [
                        [
                            'destinations' => [
                                [
                                    'to' => '528712736050'
                                ]
                            ],
                            'from' => 'InfoSMS',
                            'text' => 'El nivel del sensor ' . $sensor->ubicacion . ' está vacío',
                        ]
                    ]
                ]);
               

    
                if ($response->successful())
                 {
                    // Actualizar el tiempo de la última notificación
                    Cache::put($cacheKey, now(), now()->addMinutes(4)); // Actualizar para 4 minutos
    
                    return response()->json(['message' => 'Datos guardados y notificación enviada'], 201);
                }
    
                return response()->json(['message' => 'Datos guardados pero no se pudo enviar la notificación'], 201);
            }
        
        else{
            return response()->json(['message' => 'Datos guardados'], 201);
        }
    }
        return response()->json(['message' => 'Datos guardados', 'id' => $result->getInsertedId()], 201);
    }
    
    //OBTENER SENSORES
    public function obtenerSensores()
    {
        $sensores = Sensor::all();

        if($sensores && count($sensores) > 0)
        {
            return response()->json($sensores, 200);
        }
        else
        {
            return response()->json(['message' => 'No se encontraron sensores'], 404);
        }
    }
   
    //OBTENER SENSOR POR ID
    public function obtenerSensor($id)
    {
        $sensor = Sensor::find($id);

        if($sensor)
        {
            return response()->json($sensor, 200);
        }
        else
        {
            return response()->json(['message' => 'Sensor no encontrado'], 404);
        }
    }

    //MOSTRAR VALORES DE SENSOR
    public function show($sensorId)
    {
        try {
            // Determinar el nombre de la colección basado en el sensor_id
            $collectionName = $sensorId; // Nombre de la colección por sensor
            $collection = $this->database->selectCollection($collectionName);

            // Obtener los datos de la colección específica del sensor
            // Ordenar los resultados primero por 'fecha' y luego por 'hora' en orden descendente
            $cursor = $collection->find([], [
                'sort' => [
                    'fecha' => -1, // Orden descendente por fecha
                    'hora' => -1   // Orden descendente por hora
                ],
            ]);

            $data = iterator_to_array($cursor);

            return response()->json([
                'sensor_id' => $sensorId,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los datos del sensor'], 500);
        }
    }
    
    
}