<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cruce;
use Illuminate\Support\Facades\Validator;


class CruceController extends Controller
{
    
    public function createCruce(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perro_macho_id' => 'required|integer',
            'perro_hembra_id' => 'required|integer',
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,realizado,fallido',
            'cita_id' => 'nullable|sometimes|integer',
            'observaciones' => 'nullable|string|max:100',
        ]);


        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $cruce = Cruce::create([
            'perro_macho_id' => $request->perro_macho_id,
            'perro_hembra_id' => $request->perro_hembra_id,
            'fecha' => $request->fecha,
            'estado' => $request->estado,
            'cita_id' => $request->cita_id ? $request->cita_id : null,
            'observaciones' => $request->observaciones,
        ]);

        $cruce->save();


        if($cruce->save())
        {
            return response()->json([
                'message' => 'Cruce creado',
                'cruce' => $cruce
            ]);
        }
        else
        {
            return response()->json(['message' => 'Error al crear el cruce'], 500);
        }
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

        $cruce = Cruce::select(
            'cruces.*', 
            'perros.nombre as perro_macho', 
            'perros.imagen as perro_macho_imagen',
            'perros2.nombre as perro_hembra', 
            'perros2.imagen as perro_hembra_imagen',
            'camadas.id as camada_id', 
            
        )->join('perros', 'cruces.perro_macho_id', '=', 'perros.id')
        ->join('perros as perros2', 'cruces.perro_hembra_id', '=', 'perros2.id')
        ->leftJoin('camadas', 'cruces.id', '=', 'camadas.cruce_id')
        ->where('cruces.id', $id)
        ->first();
        
        
        return response()->json($cruce);
    }


    public function showAllCruces()
    {
        $cruces = Cruce::Select('cruces.*', 'perros.nombre as perro_macho', 'perros.imagen as perro_macho_imagen','perros2.imagen as perro_hembra_imagen','perros2.nombre as perro_hembra'
        )->join('perros', 'cruces.perro_macho_id', '=', 'perros.id'
        )->join('perros as perros2', 'cruces.perro_hembra_id', '=', 'perros2.id')->get();

        if($cruces->isEmpty())
        {
            return response()->json(['message' => 'No se encontraron cruces'], 404);
        }
        return response()->json($cruces);
    }


    public function showCrucesByPerro($id)
    {
        $cruces = Cruce::where('perro_macho_id', $id)->orWhere('perro_hembra_id', $id)->get();

        return response()->json($cruces);
    }
    

}
