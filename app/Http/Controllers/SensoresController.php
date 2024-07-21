<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Client as MongoClient;

class SensoresController extends Controller
{
    protected $client;
    protected $database;

    public function __construct()
    {
        // Conectar a MongoDB usando la URI de conexión
        $this->client = new MongoClient(env('DB_URI'));
        $this->database = $this->client->selectDatabase(env('DB_DATABASE'));
    }

    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'sensor_id' => 'required|string',
            'value' => 'required|numeric',
        ]);

        // Determinar el nombre de la colección basado en el sensor_id
        $sensorId = $request->input('sensor_id');
        $collectionName = 'sensor_' . $sensorId; // Nombre de la colección por sensor
        $collection = $this->database->selectCollection($collectionName);

        // Insertar datos en la colección específica del sensor
        $result = $collection->insertOne([
            'value' => $request->input('value'),
        ]);

        return response()->json(['message' => 'Datos guardados', 'id' => $result->getInsertedId()], 201);
    }
}