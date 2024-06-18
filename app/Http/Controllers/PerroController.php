<?php

namespace App\Http\Controllers;

use App\Models\Perro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Enums\ServerStatus;
use Illuminate\Validation\Rule;
use App\Models\PerroUser;
use App\Enums\TamañoPerro;
use App\Enums\SexoPerro;
use Illuminate\Validation\Rules\Enum;

class PerroController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     */
    public function crearPerro(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'nombre' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'edad' => 'required|int',
            'sexo' =>  'required|in:masculino,femenino',
            'peso' => 'required',
            'tamaño' =>  Rule::in(['pequeño','mediano','grande'],'required'),           //pequeño, mediano, grande
            'altura' => 'required',
            'estatus' => 'required', //1 = Activo, 0 = Inactivo
            'esterilizado' => Rule::in(['si','no'],'required'), //si, no
            'fecha_nacimiento' => 'required|date',
            'chip'=>'required|string|max:50|unique:perros',
            'tipo' => Rule::in(['cria','reproductor','venta'],'required'), //cria, reproductor, venta
            'id_raza' => 'required|int',
            'padre_id' => 'int|nullable',
            'madre_id' => 'int|nullable',
        ],
        [
            'nombre.required' => 'El nombre es requerido',
            'color.required' => 'El color es requerido',
            'edad.required' => 'La edad es requerida',
            'sexo.required' => 'El sexo es requerido',
            'peso.required' => 'El peso es requerido',
            'tamaño.required' => 'El tamaño es requerido',

            'altura.required' => 'La altura es requerida',
            'estatus.required' => 'El estatus es requerido',
            'esterilizado.required' => 'La esterilización es requerida',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'chip.required' => 'El chip es requerido',
            'tamaño.invalid' => 'El tamaño no es válido',


            'tipo.required' => 'El tipo es requerido',
            'chip.unique' => 'El chip ya está en uso',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = request()->user();

        $perro = Perro::create([
            'nombre' => $request->nombre,
            'color' => $request->color,
            'edad' => $request->edad,
            'sexo' => $request->sexo,
            'peso' => $request->peso,
            'tamaño' => $request->tamaño,
            'altura' => $request->altura,
            'estatus' => $request->estatus,
            'esterilizado' => $request->esterilizado,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'imagen' => $request->imagen ? $request->imagen : "",
            'chip' => $request->chip,
            'tipo' => $request->tipo,
            'id_raza' => $request->id_raza,
            'padre_id' => $request->padre_id ? $request->padre_id : null,
            'madre_id' => $request->madre_id ? $request->madre_id : null,
            'user_id' => $user->id,
          
        ]);

        $perro->save();

        if($perro->save())
        {
            $perro_user = PerroUser::create([
                'perro_id' => $perro->id,
                'user_id' => $user->id
            ]);

            $perro_user->save();

            
            return response()->json([
                'message' => 'Perro creado correctamente',
                'perro' => $perro
            ], 201);
        }
        else
        {
            return response()->json([
                'message' => 'Error al crear el perro'
            ], 400);
        }
    }

    public function actualizarPerro(Request $request,$id)
    {

        $perro = Perro::find($id);

        if(!$perro)
        {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(),
        [
            'nombre' => 'sometimes|string|max:255',
            'color' => 'sometimes|string|max:255',
            'edad' => 'sometimes|int',
            'sexo' =>  'sometimes|in:masculino,femenino',
            'peso' => 'sometimes',
            'tamaño' =>  Rule::in(['pequeño','mediano','grande'],'required'),           //pequeño, mediano, grande
            'altura' => 'sometimes',
            'estatus' => 'sometimes', //1 = Activo, 0 = Inactivo
            'esterilizado' => Rule::in(['si','no'],'required'), //si, no
            'fecha_nacimiento' => 'sometimes|date',
            'chip'=>'sometimes|string|max:50|unique:perros',
            'tipo' => Rule::in(['cria','reproductor','venta'],'sometimes'), //cria, reproductor, venta
            'id_raza' => 'sometimes|int',
            'padre_id' => 'int|nullable',
            'madre_id' => 'int|nullable',
        ],
        [
            'nombre.required' => 'El nombre es requerido',
            'color.required' => 'El color es requerido',
            'edad.required' => 'La edad es requerida',
            'sexo.required' => 'El sexo es requerido',
            'peso.required' => 'El peso es requerido',
            'tamaño.required' => 'El tamaño es requerido',

            'altura.required' => 'La altura es requerida',
            'estatus.required' => 'El estatus es requerido',
            'esterilizado.required' => 'La esterilización es requerida',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'chip.required' => 'El chip es requerido',
            'tamaño.invalid' => 'El tamaño no es válido',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $perro->nombre = $request->nombre;
        $perro->color = $request->color;
        $perro->edad = $request->edad;
        $perro->sexo = $request->sexo;
        $perro->peso = $request->peso;
        $perro->tamaño = $request->tamaño;
        $perro->altura = $request->altura;
        $perro->estatus = $request->estatus;
        $perro->esterilizado = $request->esterilizado;
        $perro->fecha_nacimiento = $request->fecha_nacimiento;
        $perro->imagen = $request->imagen ? $request->imagen : "";
        $perro->chip = $request->chip;
        $perro->tipo = $request->tipo;
        $perro->id_raza = $request->id_raza;
        $perro->padre_id = $request->padre_id ? $request->padre_id : null;
        $perro->madre_id = $request->madre_id ? $request->madre_id : null;


        $perro->save();

        if($perro->save())
        {
            return response()->json([
                'message' => 'Perro actualizado correctamente',
                'perro' => $perro
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Error al actualizar el perro'
            ], 400);
        }
        

    }


    public function mostrarPerro($id)
    {
        $perro = Perro::find($id);

        if($perro)
        {
            return response()->json([
                'message' => 'Perro encontrado',
                'perro' => $perro
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function mostrarPerros()
    {
        $perros = Perro::all();

        if($perros)
        {
            return response()->json([
                'message' => 'Perros encontrados',
                'perros' => $perros
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Perros no encontrados'
            ], 404);
        }
    }

    public function inhabilitarPerro($id)
    {
        $perro = Perro::find($id);

        if($perro)
        {
            $perro->estatus = 0;
            $perro->save();

            return response()->json([
                'message' => 'Perro inhabilitado',
                'perro' => $perro
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function habilitarPerro($id)
    {
        $perro = Perro::find($id);

        if($perro)
        {
            $perro->estatus = 1;
            $perro->save();

            return response()->json([
                'message' => 'Perro habilitado',
                'perro' => $perro
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }


}