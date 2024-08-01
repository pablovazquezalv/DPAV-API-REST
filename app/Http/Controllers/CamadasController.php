<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Camada;
use App\Models\Cruce;
use App\Models\Perro;
use App\Models\PerroUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
class CamadasController extends Controller
{
 
    public function createCamada(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cruce_id' => 'required|integer',
            'fecha' => 'required|date',
            'numero_machos' => 'required|integer',
            'numero_hembras' => 'required|integer',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $camada = Camada::create([
            'cruce_id' => $request->cruce_id,
            'fecha' => $request->fecha,
            'numero_machos' => $request->numero_machos,
            'numero_hembras' => $request->numero_hembras,
            'numero_total' => $request->numero_machos + $request->numero_hembras
        ]);

        $camada->save();

        if($camada->save())
        {
            return response()->json([
                'message' => 'Camada creada',
                'camada' => $camada
            ]);
        }
        else
        {
            return response()->json(['message' => 'Error al crear la camada'], 500);
        }
    }

    public function mostrarCamadas()
    {
        $camadas = Camada::Select(
            'camadas.id',
            'camadas.cruce_id',
            'camadas.fecha',
            'camadas.numero_machos',
            'camadas.numero_total',
            'camadas.hijos_registrados',
            'camadas.numero_hembras',
            'cruces.perro_macho_id',
            'cruces.perro_hembra_id',
            'perros.nombre as perro_macho',
            'perros.imagen as perro_macho_imagen',
            'perros2.nombre as perro_hembra',
            'perros2.imagen as perro_hembra_imagen'
        )
        ->join('cruces', 'camadas.cruce_id', '=', 'cruces.id')
        ->join('perros', 'cruces.perro_macho_id', '=', 'perros.id')
        ->join('perros as perros2', 'cruces.perro_hembra_id', '=', 'perros2.id')
        ->get();
        return response()->json($camadas);
    }

    public function mostrarCamada($id)
    {
        $camada = Camada::Select(
            'camadas.id',
            'camadas.cruce_id',
            
            'camadas.fecha',
            'camadas.numero_machos',
            'camadas.numero_total',
            'camadas.numero_hembras',
            'camadas.hijos_registrados',
            'cruces.perro_macho_id',
            'cruces.perro_hembra_id',
            'perros.nombre as perro_macho',
            'perros.imagen as perro_macho_imagen',
            'perros2.nombre as perro_hembra',
            'perros2.imagen as perro_hembra_imagen'
        )
        ->join('cruces', 'camadas.cruce_id', '=', 'cruces.id')
        ->join('perros', 'cruces.perro_macho_id', '=', 'perros.id')
        ->join('perros as perros2', 'cruces.perro_hembra_id', '=', 'perros2.id')
        ->where('camadas.id', $id)
        ->first();
        return response()->json($camada);
    }

    public function crearCachorro(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string|max:255',
                'distintivo' => 'string|max:55|nullable',
                'sexo' =>  'required|in:Macho,Hembra',
                'peso' => 'required',
                'tamano' => ['required', Rule::in(['Pequeño', 'Mediano', 'Grande'])], //pequeño, mediano, grande
                'estatus' => 'required', //1 = Activo, 0 = Inactivo
                'esterilizado' => ['required', Rule::in(['Si', 'No'])], //si, no
                'fecha_nacimiento' => 'required|date',
                'chip' => 'required|string|max:50|unique:perros',
                'tipo' => ['required', Rule::in(['Cria', 'Reproductor', 'Venta'])], //cria, reproductor, venta
                'id_raza' => 'required|int',
                'padre_id' => 'nullable|int',
                'madre_id' => 'nullable|int',
                'imagen' => 'max:500|nullable',
                'camada_id' => 'required|int'
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'distintivo.string' => 'El distintivo debe ser una cadena',
                'sexo.required' => 'El sexo es requerido',
                'peso.required' => 'El peso es requerido',
                'tamano.required' => 'El tamaño es requerido',
                'tamano.in' => 'El tamaño no es válido',
                'estatus.required' => 'El estatus es requerido',
                'esterilizado.required' => 'La esterilización es requerida',
                'esterilizado.in' => 'La esterilización no es válida',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
                'chip.required' => 'El chip es requerido',
                'chip.unique' => 'El chip debe ser único',
                'tipo.required' => 'El tipo es requerido',
                'tipo.in' => 'El tipo no es válido',
                'id_raza.required' => 'La raza es requerida',
                'padre_id.int' => 'El ID del padre debe ser un entero',
                'madre_id.int' => 'El ID de la madre debe ser un entero'
            ]
        );
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $user = request()->user();
    
        $imageUrl = "";
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $route = Storage::disk('s3')->put('images', $file);
            $imageUrl = Storage::disk('s3')->url($route);
        }
    
        $perro = Perro::create([
            'nombre' => $request->nombre,
            'distintivo' => $request->distintivo ? $request->distintivo : "",
            'sexo' => $request->sexo,
            'peso' => $request->peso,
            'tamano' => $request->tamano,
            'estatus' => $request->estatus,
            'esterilizado' => $request->esterilizado,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'chip' => $request->chip,
            'tipo' => $request->tipo,
            'id_raza' => $request->id_raza,
            'padre_id' => $request->padre_id,
            'madre_id' => $request->madre_id,
            'user_id' => $user->id,
            'imagen' => $imageUrl
        ]);
    
        if ($perro) {
            $perro_user = PerroUser::create([
                'perro_id' => $perro->id,
                'user_id' => $user->id
            ]);
    
            // Sumar 1 a la cantidad de perros del usuario
            $camada = Camada::find($request->camada_id);
           
            // Guardar los cambios en la camada
          
            
            if ($camada->hijos_registrados == $camada->numero_total) {
                return response()->json([
                    'message' => 'Camada completada'
                ]);
            }
            else{
                $camada->hijos_registrados += 1;
                $camada->save();
              
                return response()->json([
                    'mascota' => $perro
                ], 201);
            }
    
           
        } else {
            return response()->json([
                'message' => 'Error al crear el perro'
            ], 400);
        }
    }
    

}
