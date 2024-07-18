<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cruce;

class CruceController extends Controller
{
    
    public function createCruce(Request $request)
    {
        $request->validate([
            'perro_macho_id' => 'required|integer',
            'perro_hembra_id' => 'required|integer',
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,realizado,fallido',
            'cita_id' => 'nullable|integer',
            'observaciones' => 'nullable|string|max:100',
        ]);

        $cruce = Cruce::create($request->all());

        return response()->json([
            'message' => 'Cruce creado',
            'cruce' => $cruce
        ], 201);
    }


    public function updateCruce(Request $request, $id)
    {
        $request->validate([
            'perro_macho_id' => 'required|integer',
            'perro_hembra_id' => 'required|integer',
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,realizado,fallido',
            'cita_id' => 'nullable|integer',
            'observaciones' => 'nullable|string|max:100',
        ]);

        $cruce = Cruce::find($id);

        if($cruce == null)
        {
            return response()->json(['message' => 'No se encontro el cruce'], 404);
        }

        $cruce->update($request->all());

        return response()->json([
            'message' => 'Cruce actualizado',
            'cruce' => $cruce
        ]);
    }


    public function deleteCruce($id)
    {
        $cruce = Cruce::find($id);

        if($cruce == null)
        {
            return response()->json(['message' => 'No se encontro el cruce'], 404);
        }

        $cruce->delete();

        return response()->json(['message' => 'Cruce eliminado']);
    }


    public function showCruce($id)
    {
        $cruce = Cruce::find($id);

        if($cruce == null)
        {
            return response()->json(['message' => 'No se encontro el cruce'], 404);
        }

        return response()->json($cruce);
    }


    public function showAllCruces()
    {
        $cruces = Cruce::all();

        return response()->json($cruces);
    }


    public function showCrucesByPerro($id)
    {
        $cruces = Cruce::where('perro_macho_id', $id)->orWhere('perro_hembra_id', $id)->get();

        return response()->json($cruces);
    }
    

}
