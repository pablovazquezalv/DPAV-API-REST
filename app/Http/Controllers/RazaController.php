<?php

namespace App\Http\Controllers;

use App\Models\Raza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RazaController extends Controller
{
   
    //MOSTRAR TODAS LAS RAZAS
    public function mostrarRazas()
    {
        $raza = Raza::all();

        return response()->json($raza);
    }

    //MOSTRAR RAZA POR ID
    public function mostrarRaza($id)
    {
        $raza = Raza::find($id);

        if($raza == null)
        {
            return response()->json(['message' => 'No se encontro la raza'], 404);
        }

        return response()->json($raza, 200);
    }

    
    //CREAR RAZA
    public function crearRaza(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:2',
        ],[
            'nombre.required' => 'El nombre es requerido',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $raza = Raza::create(
            [
                'nombre' => $request->nombre,
            ]
        );

        $raza->save();

        if($raza->save())
        {
            return response()->json($raza, 201);
        }
        else
        {
            return response()->json($raza, 400);
        }
    
    }

    //ACTUALIZAR RAZA
    public function actualizarRaza(Request $request,$id)
    {
        $raza = Raza::find($id);

        if($raza == null)
        {
            return response()->json(['message' => 'No se encontro la raza'], 404);
        }

        $validate = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ],[
            'nombre.required' => 'El nombre es requerido',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $raza->nombre = $request->nombre;
        $raza->save();

        if($raza->save())
        {
            return response()->json($raza, 200);
        }
        else
        {
            return response()->json($raza, 400);
        }

    }
    
    //ELIMINAR RAZA
    public function inahabilitarRaza($id)
    {
        $raza = Raza::find($id);

        if($raza == null)
        {
            return response()->json(['message' => 'No se encontro la raza'], 404);
        }

        $raza->estado = 0;
        $raza->save();

        return response()->json($raza, 200);
    }

    //HABILITAR RAZA
    public function habilitarRaza($id)
    {
        $raza = Raza::find($id);

        if($raza == null)
        {
            return response()->json(['message' => 'No se encontro la raza'], 404);
        }

        $raza->estado = 1;
        $raza->save();

        return response()->json($raza, 200);
    }

    public function mostrarRazasHabilitadas()
    {
        $raza = Raza::where('estado',1)->get();

        return response()->json($raza);
    }

    public function mostrarRazasInhabilitadas()
    {
        $raza = Raza::where('estado',0)->get();

        return response()->json($raza);
    }
}
