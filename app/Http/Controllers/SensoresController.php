<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use MongoDB\Client as MongoClient;
use App\Models\Sensor;
use Symfony\Component\HttpFoundation\StreamedResponse;


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


    public function mandarDatosMongo(Request $request)
    {
        // Validar datos
        $validator = Validator::make(
            $request->all(),
            [
                'sensor_id' => 'required|string',
                'value' => 'required|numeric',
                'nivel' => ['required', Rule::in(['bajo', 'medio', 'lleno', 'vacio'])],
            ],
            [
                'sensor_id.required' => 'El campo sensor_id es obligatorio',
                'sensor_id.string' => 'El campo sensor_id debe ser una cadena de texto',
                'value.required' => 'El campo value es obligatorio',
                'value.numeric' => 'El campo value debe ser un número',
                'nivel.required' => 'El campo nivel es obligatorio',
                'nivel.in' => 'El campo nivel debe ser uno de los siguientes valores: bajo, medio, lleno, vacio',
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Determinar el nombre de la colección basado en el sensor_id
        $sensorId = $request->input('sensor_id');
        $collectionName = $sensorId; // Nombre de la colección por sensor
        $collection = $this->database->selectCollection($collectionName);
    
        // Insertar datos en la colección específica del sensor
        $result = $collection->insertOne([
            'sensor_id' => $sensorId,
            'value' => $request->input('value'),
            'nivel' => $request->input('nivel'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    
        $this->emitEvent($sensorId, [
            'sensor_id' => $sensorId,
            'value' => $request->input('value'),
            'nivel' => $request->input('nivel'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    
        return response()->json(['message' => 'Datos guardados', 'id' => $result->getInsertedId()], 201);
    }
    
    private function emitEvent($sensorId, $data)
    {
        event(new \App\Events\SensorDataUpdated($sensorId, $data));
    }
    
    // Método para manejar la transmisión de datos en tiempo real
    public function streamSensorData($sensorId)
    {
        $response = new StreamedResponse(function () use ($sensorId) {
            while (true) {
                echo 'data: ' . json_encode(['sensor_id' => $sensorId, 'timestamp' => now()]) . "\n\n";
                ob_flush();
                flush();
                sleep(5); // Intervalo de tiempo para la emisión de eventos
            }
        });
    
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
    
        return $response;
    }

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



   

    public function show($sensorId)
    {
        try {
            // Determinar el nombre de la colección basado en el sensor_id
            $collectionName = $sensorId; // Nombre de la colección por sensor
            $collection = $this->database->selectCollection($collectionName);
    
            // Obtener los datos de la colección específica del sensor
            $cursor = $collection->find();
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