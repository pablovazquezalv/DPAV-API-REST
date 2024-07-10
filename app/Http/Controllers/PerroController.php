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
use App\Models\User;

class PerroController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function crearPerro(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string|max:255',
                'distintivo' => 'string|max:55|nullable',
                'sexo' =>  'required|in:Macho,Hembra',
                'peso' => 'required',
                'tamano' =>  Rule::in(['Pequeño', 'Mediano', 'Grande'], 'required'),           //pequeño, mediano, grande
                'estatus' => 'required', //1 = Activo, 0 = Inactivo
                'esterilizado' => Rule::in(['Si', 'No'], 'required'), //si, no
                'fecha_nacimiento' => 'required|date',
                'chip' => 'required|string|max:50|unique:perros',
                'tipo' => Rule::in(['Cria', 'Reproductor', 'Venta'], 'required'), //cria, reproductor, venta
                'id_raza' => 'required|int',
                'padre_id' => 'nullable',
                'madre_id' => 'nullable',
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
            'imagen' => $request->imagen,
            'chip' => $request->chip,
            'tipo' => $request->tipo,
            'id_raza' => $request->id_raza,
            'padre_id' => $request->padre_id ? $request->padre_id : null,
            'madre_id' => $request->madre_id ? $request->madre_id : null,
            'user_id' => $user->id,
            'imagen' => $imageUrl ? $imageUrl : ''
        ]);

        $perro->save();

        if ($perro->save()) {
            $perro_user = PerroUser::create([
                'perro_id' => $perro->id,
                'user_id' => $user->id
            ]);

            $perro_user->save();


            return response()->json([
                'message' => 'Perro creado correctamente',
                'perro' => $perro
            ], 201);
        } else {
            return response()->json([
                'message' => 'Error al crear el perro'
            ], 400);
        }
    }

    public function actualizarPerro(Request $request, $id)
    {

        $perro = Perro::find($id);

        if (!$perro) {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string|max:255',
                'distintivo' => 'string|max:55|nullable', //distintivo
                'sexo' =>  'sometimes|in:M,F',
                'peso' => 'sometimes',
                'tamano' =>  Rule::in(['Pequeño', 'Mediano', 'Grande'], 'sometimes'),           //pequeño, mediano, grande
                'estatus' => 'required', //1 = Activo, 0 = Inactivo
                'esterilizado' => Rule::in(['Si', 'No'], 'required'), //si, no
                'fecha_nacimiento' => 'sometimes|date',
                'chip' => 'sometimes|string|max:50',
                'tipo' => Rule::in(['Cria', 'Reproductor', 'Venta'], 'sometimes'), //cria, reproductor, venta
                'id_raza' => 'required|int',
                'padre_id' => 'sometimes|nullable',
                'madre_id' => 'sometimes|nullable',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

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
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = request()->user();


        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $route = Storage::disk('s3')->put('images', $file);
            $imageUrl = Storage::disk('s3')->url($route);
        } else {
            $imageUrl = "";
        }

        $perro->nombre = $request->nombre;
        $perro->distintivo = $request->distintivo ? $request->distintivo : "";
        $perro->sexo = $request->sexo;
        $perro->peso = $request->peso;
        $perro->tamano = $request->tamano ? $request->tamano : "";
        $perro->estatus = $request->estatus == "1" ? 1 : 0;
        $perro->esterilizado = $request->esterilizado;
        $perro->fecha_nacimiento = $request->fecha_nacimiento;
        $perro->imagen = $request->imagen ? $request->imagen : "";
        $perro->chip = $request->chip == null ? $perro->chip : $request->chip;
        $perro->tipo = $request->tipo;
        $perro->id_raza = $request->id_raza;
        $perro->padre_id = $request->padre_id ? $request->padre_id : null;
        $perro->madre_id = $request->madre_id ? $request->madre_id : null;

        $perro->user_id = $user->id;
        $perro->imagen = $imageUrl ? $imageUrl : '';

        $perro->save();

        if ($perro->save()) {
            return response()->json([
                'message' => 'Perro actualizado correctamente',
                'perro' => $perro
            ], 200);
        } else {
            return response()->json([
                'message' => 'Error al actualizar el perro'
            ], 400);
        }
    }

    public function mostrarPerro($id)
    {

        $perro_usuario = User::Select(
            'users.id',
            'users.nombre',
            'users.apellido_paterno',
            'users.telefono',
            'users.email',
            'users.direccion',
            'users.ciudad',
            'users.estado_id',
            'users.codigo_postal',
            'perros.id as perro_id',
            'perros.nombre as perro_nombre',
            'perros.distintivo',
            'perros.sexo as perro_sexo',
            'perros.peso as perro_peso',
            'perros.tamano',
            'perros.estatus as perro_estatus',
            'perros.esterilizado',
            'perros.fecha_nacimiento',
            'perros.chip as perro_chip',
            'perros.tipo',
            'perros.id_raza',
            'perros.padre_id',
            'perros.madre_id',
            'perros.imagen',
            'razas.nombre as raza'
        )
            ->join('perros', 'users.id', '=', 'perros.user_id')
            ->join('razas', 'perros.id_raza', '=', 'razas.id')
            ->where('perros.id', $id)
            ->first();



        if ($perro_usuario) {
            return response()->json([
                'message' => 'Perro encontrado',
                'perro' => $perro_usuario
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function mostrarPerros()
    {
        $perros = Perro::all();

        if ($perros) {
            return response()->json([
                'message' => 'Perros encontrados',
                'perros' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perros no encontrados'
            ], 404);
        }
    }

    public function inhabilitarPerro($id)
    {
        $perro = Perro::find($id);

        if ($perro) {
            $perro->estatus = 0;
            $perro->save();

            return response()->json([
                'message' => 'Perro inhabilitado',
                'perro' => $perro
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function habilitarPerro($id)
    {
        $perro = Perro::find($id);

        if ($perro) {
            $perro->estatus = 1;
            $perro->save();

            return response()->json([
                'message' => 'Perro habilitado',
                'perro' => $perro
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function mostrarPerrosEnVenta()
    {
        $perros = Perro::where('tipo', 'Venta')->get();

        if ($perros) {
            return response()->json([
                'message' => 'Perros en venta encontrados',
                'perros' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perros en venta no encontrados'
            ], 404);
        }
    }

    public function mostrarPerrosEnVentaPorId($id)
    {
        $perros = Perro::where('tipo', 'Venta')->where('id', $id)->get();

        if ($perros) {
            return response()->json([
                'message' => 'Perros en venta encontrados',
                'perros' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perros en venta no encontrados'
            ], 404);
        }
    }

    public function buscarPerroPorChip($chip)
    {
        $perro = Perro::where('chip', $chip)->get();

        if ($perro->count() > 0) {
            return response()->json([
                'message' => 'Perro encontrado',
                'perro' => $perro
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function buscarPerras()
    {
        $perras = Perro::where('sexo', 'Hembra')->get();

        if ($perras->count() > 0) {
            return response()->json([
                'message' => 'Perro encontrado',
                'perras' => $perras
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function buscarPerros()
    {
        $perros = Perro::where('sexo', 'Macho')->get();

        if ($perros->count() > 0) {
            return response()->json([
                'message' => 'Perro encontrado',
                'perros' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }


    public function mostrarPerrosRecientes()
    {

        $user = request()->user();

        $perros = Perro::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(5)->get();

        if ($perros) {
            return response()->json([
                'message' => 'Perros encontrados',
                'perros' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perros no encontrados'
            ], 404);
        }
    }

    public function mostrarPerrosPorUsuario()
    {
        $user = request()->user();

        $perros = Perro::where('user_id', $user->id)->get();

        if ($perros->count() > 0) {
            return response()->json([
                'message' => 'Perros encontrados',
                'perros' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perros no encontrados'
            ], 404);
        }
    }

    public function guardaPerro(Request $request, $id = null)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string|max:255',
                'distintivo' => 'string|max:55|nullable',
                'sexo' => 'required|in:Macho,Hembra',
                'peso' => 'required',
                'tamano' => 'required|in:Pequeño,Mediano,Grande',
                'estatus' => 'required',
                'esterilizado' => 'required|in:Si,No',
                'fecha_nacimiento' => 'required|date',
                'chip' => 'required|string|max:50',
                'tipo' => 'required|in:Cria,Reproductor,Venta',
                'id_raza' => 'required|int',
                'padre_id' => 'nullable',
                'madre_id' => 'nullable',
                'imagen' => 'string|nullable',
            ],
            [
                'nombre.required' => 'El nombre es requerido',
                'distintivo.required' => 'El distintivo es requerido',
                'sexo.required' => 'El sexo es requerido',
                'peso.required' => 'El peso es requerido',
                'tamano.required' => 'El tamaño es requerido',
                'estatus.required' => 'El estatus es requerido',
                'esterilizado.required' => 'La esterilización es requerida',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es requerida',
                'chip.required' => 'El chip es requerido',
                'tipo.required' => 'El tipo es requerido',
                'id_raza.required' => 'La raza es requerida',
                'tamano.invalid' => 'El tamaño no es válido',
                'imagen.required' => 'La imagen es requerida',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $user = $request->user();

            if ($id) {
                $perro = Perro::findOrFail($id);

                if ($perro->user_id != $user->id) {
                    return response()->json(['message' => 'No autorizado'], 403);
                }
            } else {
                $perro = new Perro();
                $perro->user_id = $user->id;
            }

            $perro->nombre = $request->nombre;
            $perro->distintivo = $request->distintivo ?: "";
            $perro->sexo = $request->sexo;
            $perro->peso = $request->peso;
            $perro->tamano = $request->tamano ?: "";
            $perro->estatus = $request->estatus == "1" ? 1 : 0;
            $perro->esterilizado = $request->esterilizado;
            $perro->fecha_nacimiento = $request->fecha_nacimiento;
            $perro->imagen = $request->imagen ?: "";
            $perro->chip = $request->chip;
            $perro->tipo = $request->tipo;
            $perro->id_raza = $request->id_raza;
            $perro->padre_id = $request->padre_id ?: null;
            $perro->madre_id = $request->madre_id ?: null;

            $perro->save();

            $message = $id ? 'Perro actualizado correctamente' : 'Perro creado correctamente';

            return response()->json([
                'message' => $message,
                'perro' => $perro
            ], $id ? 200 : 201);
        } catch (\Exception $e) {
            $message = $id ? 'Error al actualizar el perro' : 'Error al crear el perro';
            return response()->json([
                'message' => $message,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
