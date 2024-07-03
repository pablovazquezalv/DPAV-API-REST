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
use App\Enums\TipoPerro;
use App\Enums\EstatusPerro;
use App\Enums\EsterilizadoPerro;
use App\Enums\TamanoPerro;
use Illuminate\Support\Facades\Storage;
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
            'distintivo' => 'string|max:55|nullable',
            'sexo' =>  'required|in:M,F',
            'peso' => 'required',
            'tamano' =>  Rule::in(['pequeño','mediano','grande'],'required'),           //pequeño, mediano, grande
            'estatus' => 'required', //1 = Activo, 0 = Inactivo
            'esterilizado' => Rule::in(['si','no'],'required'), //si, no
            'fecha_nacimiento' => 'required|date',
            'chip'=>'required|string|max:50|unique:perros',
            'tipo' => Rule::in(['cria','reproductor','venta'],'required'), //cria, reproductor, venta
            'id_raza' => 'required|int',
            'padre_id' => 'int|nullable',
            'madre_id' => 'int|nullable',
            'imagen' => 'string|max:500|nullable',
        ],
        [
            'nombre.required' => 'El nombre es requerido',
            'distintivo.required' => 'El distintivo es requerido',
            'sexo.required' => 'El sexo es requerido',
            'peso.required' => 'El peso es requerido',
            'tamano.required' => 'El tamano es requerido',
            'estatus.required' => 'El estatus es requerido',
            'esterilizado.required' => 'La esterilización es requerida',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'chip.required' => 'El chip es requerido',
            'tipo.required' => 'El tipo es requerido',
            'id_raza.required' => 'La raza es requerida',
            'tamano.invalid' => 'El tamano no es válido',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = request()->user();

        
        if($request->hasFile('imagen'))
        {
            $file = $request->file('imagen');
            $route = Storage::disk('s3')->put('images', $file);
            $imageUrl = Storage::disk('s3')->url($route);
        }
        else
        {
            $imageUrl = "";
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
            'imagen' => $request->imagen ? $request->imagen : "",
            'chip' => $request->chip,
            'tipo' => $request->tipo,
            'id_raza' => $request->id_raza,
            'padre_id' => $request->padre_id ? $request->padre_id : null,
            'madre_id' => $request->madre_id ? $request->madre_id : null,
            'user_id' => $user->id,
            'imagen' => $imageUrl ? $imageUrl : ''
          
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
            'nombre' => 'required|string|max:255',
           'distintivo' => 'string|max:55|nullable', //distintivo
            'sexo' =>  'required|in:M,F',
            'peso' => 'required',
            'tamano' =>  Rule::in(['pequeño','mediano','grande'],'required'),           //pequeño, mediano, grande
            'estatus' => 'required', //1 = Activo, 0 = Inactivo
            'esterilizado' => Rule::in(['si','no'],'required'), //si, no
            'fecha_nacimiento' => 'required|date',
            'chip'=>'sometimes|string|max:50',
            'tipo' => Rule::in(['cria','reproductor','venta'],'required'), //cria, reproductor, venta
            'id_raza' => 'required|int',
            'padre_id' => 'int|nullable',
            'madre_id' => 'int|nullable',
            'imagen' => 'string|max:500|nullable',
        
        ],
        [
            'nombre.required' => 'El nombre es requerido',
            'distintivo.required' => 'El distintivo es requerido',
            'sexo.required' => 'El sexo es requerido',
            'peso.required' => 'El peso es requerido',
            'tamano.required' => 'El tamano es requerido',
            'altura.required' => 'La altura es requerida',
            'estatus.required' => 'El estatus es requerido',
            'esterilizado.required' => 'La esterilización es requerida',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
            'chip.required' => 'El chip es requerido',
            'tipo.required' => 'El tipo es requerido',
            'id_raza.required' => 'La raza es requerida',
            'tamano.invalid' => 'El tamano no es válido',
            'imagen.required' => 'La imagen es requerida',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = request()->user();


        if($request->hasFile('imagen'))
        {
            $file = $request->file('imagen');
            $route = Storage::disk('s3')->put('images', $file);
            $imageUrl = Storage::disk('s3')->url($route);
        }
        else
        {
            $imageUrl = "";
        }

        $perro->nombre = $request->nombre;
        $perro->distintivo = $request->distintivo ? $request->distintivo : "";
        $perro->sexo = $request->sexo;
        $perro->peso = $request->peso;
        $perro->tamano = $request->tamano;
        $perro->estatus = $request->estatus;
        $perro->esterilizado = $request->esterilizado;
        $perro->fecha_nacimiento = $request->fecha_nacimiento;
        $perro->imagen = $request->imagen ? $request->imagen : "";
        $perro->chip = $request->chip;
        $perro->tipo = $request->tipo;
        $perro->id_raza = $request->id_raza;
        $perro->padre_id = $request->padre_id ? $request->padre_id : null;
        $perro->madre_id = $request->madre_id ? $request->madre_id : null;
        $perro->user_id = $user->id;
        $perro->imagen = $imageUrl ? $imageUrl : '';

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

    public function mostrarPerrosEnVenta()
    {
        $perros = Perro::where('tipo','venta')->get();

        if($perros)
        {
            return response()->json([
                'message' => 'Perros en venta encontrados',
                'perros' => $perros
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Perros en venta no encontrados'
            ], 404);
        }
    }

    #una ruta que solo muestre los perros en venta segun el id

    public function mostrarPerrosEnVentaPorId($id)
    {
        $perros = Perro::where('tipo','venta')->where('id',$id)->get();

        if($perros)
        {
            return response()->json([
                'message' => 'Perros en venta encontrados',
                'perros' => $perros
            ], 200);
        }
        else
        {
            return response()->json([
                'message' => 'Perros en venta no encontrados'
            ], 404);
        }
    }

}
