<?php

namespace App\Http\Controllers;

use App\Models\Perro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PerroUser;
use Illuminate\Validation\Rule;
use App\Http\Requests\PerroRequest;
use Inertia\Inertia;

class PerroController extends Controller
{
    
    // Crear perro
    public function crearPerro(PerroRequest $request)
    {
        $user = $request->user();
        
        $perro = Perro::create([
            'nombre' => $request->nombre,
            'color' => $request->color,
            'edad' => $request->edad,
            'sexo' => $request->sexo,
            'peso' => $request->peso,
            'tamaño' => $request->tamaño,
            'altura' => $request->altura,
            'estatus' => $request->estatus ?? 1,
            'esterilizado' => $request->esterilizado,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'id_raza' => $request->id_raza,
            'user_id' => $user->id,
        ]);

        // Asociar el perro al usuario
        PerroUser::create([
            'perro_id' => $perro->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Perro creado correctamente',
            'perro' => $perro
        ], 201);
    }

    // Actualizar perro
    public function actualizarPerro(PerroRequest $request, $id)
    {
        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json(['message' => 'Perro no encontrado'], 404);
        }

        $perro->update($request->only([
            'nombre', 'color', 'edad', 'sexo', 'peso', 'tamaño', 'altura', 'estatus', 'esterilizado', 'fecha_nacimiento', 'id_raza'
        ]));

        return response()->json([
            'message' => 'Perro actualizado correctamente',
            'perro' => $perro
        ], 200);
    }
    // Mostrar perro por ID
    public function mostrarPerro($id)
    {
        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json(['message' => 'Perro no encontrado'], 404);
        }

        return response()->json([
            'message' => 'Perro encontrado',
            'perro' => $perro
        ], 200);
    }

    // Eliminar perro 
    public function eliminarPerro($id)
    {
        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json(['message' => 'Perro no encontrado'], 404);
        }

        // Eliminar la relación de PerroUser
        $perro->usuarios()->delete();
        $perro->delete();

        return response()->json(['message' => 'Perro eliminado correctamente'], 200);
    }


    // Mostrar perros
    public function mostrarPerros(Request $request)
    {
        $user = $request->user();

        if ($user->role_id == 1) {
            $perros = Perro::with('raza', 'user')->get();
        } elseif ($user->role_id == 3) {
            $perros = Perro::with('raza')->where('user_id', $user->id)->get();
        } else {
            return response()->json(['message' => 'No tienes permiso para ver esta información'], 403);
        }

        if ($perros->isEmpty()) {
            return response()->json(['message' => 'No se encontraron perros'], 404);
        }

        return response()->json(['message' => 'Perros encontrados', 'perros' => $perros], 200);
    }

    // Inhabilitar perro
    public function inhabilitarPerro($id)
    {
        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json(['message' => 'Perro no encontrado'], 404);
        }

        $perro->estatus = 0;
        $perro->save();

        return response()->json([
            'message' => 'Perro inhabilitado',
            'perro' => $perro
        ], 200);
    }

    // Habilitar perro

    public function habilitarPerro($id)
    {
        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json(['message' => 'Perro no encontrado'], 404);
        }

        $perro->estatus = 1;
        $perro->save();

        return response()->json([
            'message' => 'Perro habilitado',
            'perro' => $perro
        ], 200);
    }
    public function index()
    {
        return Inertia::render('Perros', [
            'dato' => 'valor', // Este dato es lo que recibirás en tu componente React
        ]);
    }
}
