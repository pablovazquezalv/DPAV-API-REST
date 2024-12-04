<?php

namespace App\Http\Controllers;

use App\Models\Perro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PerroUser;
use Illuminate\Validation\Rule;
use App\Http\Requests\PerroRequest;
use Inertia\Inertia;
use App\Models\Raza;

class PerroController extends Controller
{
    public function actualizarPerro(Request $request, $id)
    {
        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json(['message' => 'Perro no encontrado'], 404);
        }

        $perro->update($request->only([
            'nombre', 'color', 'edad', 'sexo', 'peso', 'tamaño', 'altura', 'estatus', 'esterilizado', 'fecha_nacimiento', 'id_raza'
        ]));

        return view('app', ['perros' => Perro::all()]);
    }
    // Mostrar perro por ID
    

    // Eliminar perro 
    public function eliminarPerro($id)
    {
        $perro = Perro::find($id);
     

        if (!$perro) {
            return redirect()->back()->with('error', 'Perro no encontrado');
        }

        $perro->delete();
        return redirect()->back()->with('success', 'Perro eliminado correctamente');
    }



    // Mostrar perros
    public function mostrarPerros(Request $request)
    {
       
        $perros = Perro::where('estatus', 1)->get();

        return response()->json([
            'message' => 'Perros encontrados',
            'perros' => $perros
        ], 200);
     
    }

    public function crearPerro(Request $request)
    {
        $validator = Validator::make($request->all(), [
           
            'nombre' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'edad' => 'required|string|max:50',
            'sexo' => 'required|in:Macho,Hembra',
            'peso' => 'required|string|max:50',
            'tamaño' => 'required|in:Pequeño,Mediano,Grande',
            'fecha_nacimiento' => 'required|date',
            'id_raza' => 'required|exists:razas,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $perro = Perro::create([
            'nombre' => $request->nombre,
            'color' => $request->color,
            'edad' => $request->edad,
            'sexo' => $request->sexo,
            'estatus' => 1,
            'peso' => $request->peso,
            'tamaño' => $request->tamaño,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'id_raza' => $request->id_raza,
            'user_id' => 1
            
        ]);

        return view('app', ['perros' => Perro::all()]);
    }


    public function IndexPerrosView()
    {
        $perros = Perro::all(); 
        return view('app', ['perros' => $perros]);
    }

    public function CreatePerrosView()
    {
        $razas = Raza::all(); 
        return view('crear-perro', ['razas' => $razas]); 
    }
    public function mostrarPerroView($id)
    {
        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json(['message' => 'Perro no encontrado'], 404);
        }

        return view('editar-perro', ['perro' => $perro]);
    }


      // Inhabilitar perro
    // public function inhabilitarPerro($id)
    // {
    //     $perro = Perro::find($id);

    //     if (!$perro) {
    //         return response()->json(['message' => 'Perro no encontrado'], 404);
    //     }

    //     $perro->estatus = 0;
    //     $perro->save();

    //     return response()->json([
    //         'message' => 'Perro inhabilitado',
    //         'perro' => $perro
    //     ], 200);
    // }

    // Habilitar perro

    // public function habilitarPerro($id)
    // {
    //     $perro = Perro::find($id);

    //     if (!$perro) {
    //         return response()->json(['message' => 'Perro no encontrado'], 404);
    //     }

    //     $perro->estatus = 1;
    //     $perro->save();

    //     return response()->json([
    //         'message' => 'Perro habilitado',
    //         'perro' => $perro
    //     ], 200);
    // }

    
}
