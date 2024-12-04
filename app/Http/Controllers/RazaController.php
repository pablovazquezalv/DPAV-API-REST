<?php

namespace App\Http\Controllers;

use App\Models\Raza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RazaController extends Controller
{
   
    public function mostrarRazas()
    {
        $razas = Raza::all();
        return response()->json($razas);
    }

  
    public function mostrarRaza($id)
    {
        $raza = Raza::find($id);

        if (is_null($raza)) {
            return response()->json(['message' => 'No se encontró la raza'], 404);
        }

        return response()->json($raza, 200);
    }

    /*
    * Crea una raza
    */
    public function crearRaza(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre es requerido',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $raza = Raza::create([
            'nombre' => $request->nombre,
        ]);

        return response()->json($raza, 201);
    }

    /*
    * Actualiza una raza
    */
    public function actualizarRaza(Request $request, $id)
    {
        $raza = Raza::find($id);

        if (is_null($raza)) {
            return response()->json(['message' => 'No se encontró la raza'], 404);
        }

        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre es requerido',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $raza->nombre = $request->nombre;
        $raza->save();

        return response()->json($raza, 200);
    }

    /*
    * Eliminar una raza
    */
    public function inhabilitarRaza($id)
    {
        $raza = Raza::find($id);

        if (is_null($raza)) {
            return response()->json(['message' => 'No se encontró la raza'], 404);
        }

        $raza->estado = 0;
        $raza->save();

        return response()->json($raza, 200);
    }

    /*
    * Habilitar una raza
    */
    public function habilitarRaza($id)
    {
        $raza = Raza::find($id);

        if (is_null($raza)) {
            return response()->json(['message' => 'No se encontró la raza'], 404);
        }

        $raza->estado = 1;
        $raza->save();

        return response()->json($raza, 200);
    }

    /*
    * Mostrar razas habilitadas
    */
    public function mostrarRazasHabilitadas()
    {
        $razas = Raza::where('estado', 1)->get();
        return response()->json($razas);
    }

    /*
    * Mostrar razas inhabilitadas
    */
    public function mostrarRazasInhabilitadas()
    {
        $razas = Raza::where('estado', 0)->get();
        return response()->json($razas);
    }
}
