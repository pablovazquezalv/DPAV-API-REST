<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use MongoDB\Client as MongoClient;

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

    public function store(Request $request)
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
        $collectionName = 'sensor_' . $sensorId; // Nombre de la colección por sensor
        $collection = $this->database->selectCollection($collectionName);
    
        // Insertar datos en la colección específica del sensor
        $result = $collection->insertOne([
            'sensor_id' => $sensorId,
            'value' => $request->input('value'),
            'nivel' => $request->input('nivel'),
        ]);
    
        return response()->json(['message' => 'Datos guardados', 'id' => $result->getInsertedId()], 201);
    }
}