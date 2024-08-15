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
    
     
    
        return response()->json(['message' => 'Datos guardados', 'id' => $result->getInsertedId()], 201);
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
            // Ordenar los resultados por 'created_at' en orden descendente
            $cursor = $collection->find([], [
                'sort' => ['created_at' => -1], // -1 para orden descendente
                'limit' => 100 // Limitar a los primeros 100 resultados

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