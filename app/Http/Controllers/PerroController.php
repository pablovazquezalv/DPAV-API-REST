<?php

namespace App\Http\Controllers;

use App\Models\Perro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Enums\ServerStatus;
use Illuminate\Validation\Rule;
use App\Models\PerroUser;
use App\Models\Gps;
use App\Models\Zona;
use Illuminate\Support\Facades\DB;

use App\Enums\TamañoPerro;
use App\Enums\SexoPerro;
use App\Enums\TipoPerro;
use App\Enums\EstatusPerro;
use App\Enums\EsterilizadoPerro;
use App\Enums\TamanoPerro;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use App\Models\User;

use Dompdf\Dompdf;

use Spatie\LaravelPdf\Facades\Pdf;


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
                'imagen' => 'max:500|nullable',
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
        else {
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
            'chip' => $request->chip,
            'tipo' => $request->tipo,
            'id_raza' => $request->id_raza,
            'padre_id' => $request->padre_id != null ? $request->padre_id : null,
            'madre_id' => $request->madre_id != null ? $request->madre_id : null,
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
                'mascota' => $perro
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
                'distintivo' => 'string|max:55|nullable',
                'sexo' =>  'required|in:Macho,Hembra',
                'peso' => 'required',
                'tamano' =>  Rule::in(['Pequeño', 'Mediano', 'Grande'], 'required'),           //pequeño, mediano, grande
                'estatus' => 'required', //1 = Activo, 0 = Inactivo
                'esterilizado' => Rule::in(['Si', 'No'], 'required'), //si, no
                'fecha_nacimiento' => 'required|date',
                'chip' => 'required|string|max:50',
                'tipo' => Rule::in(['Cria', 'Reproductor', 'Venta'], 'required'), //cria, reproductor, venta
                'id_raza' => 'required|int',
                'padre_id' => 'nullable',
                'madre_id' => 'nullable',
                'imagen' => 'max:500|nullable',

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
            $imageUrl = $request->imagen;
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
        $request->padre_id != null ? $request->padre_id : null;
        $request->madre_id != null ? $request->madre_id : null;
    
      
    
        $perro->imagen = $imageUrl ? $imageUrl : '';

        $perro->save();

        if ($perro->save()) {
            return response()->json([
                'message' => 'Perro actualizado correctamente',
                'mascota' => $perro
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
            'users.estado',
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
                'mascota' => $perro_usuario
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perro no encontrado'
            ], 404);
        }
    }

    public function mostrarPerros()
    {
        $perros = User::Select(
            'users.id',
            'users.nombre',
            'users.apellido_paterno',
            'users.telefono',
            'users.email',
            'users.direccion',
            'users.ciudad',
            'users.estado',
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
            
            ->get();


        if ($perros) {
            return response()->json([
                'message' => 'Mascotas encontrados',
                'mascotas' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Mascotas no encontrados'
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
                'mascota' => $perros
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
                'message' => 'Perras encontradas',
                'perras' => $perras
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perras no encontradas'
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
                'mascotas' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Perros no encontrados'
            ], 404);
        }
    }

    public function mostrarPerrosPorUsuario()
    {
        try{
        $user = request()->user();

        $perros = Perro::select
        (
            'perros.id',
            'perros.nombre',
            'perros.distintivo',
            'perros.sexo',
            'perros.peso',
            'perros.tamano',
            'perros.estatus',
            'perros.esterilizado',
            'perros.fecha_nacimiento',
            'perros.chip',
            'perros.tipo',
            'perros.id_raza',
            'perros.padre_id',
            'perros.madre_id',
            'perros.imagen',
            'razas.nombre as raza'
        )
        ->join('razas', 'perros.id_raza', '=', 'razas.id')
        ->where('perros.user_id', $user->id)
        ->get();

        if ($perros->count() > 0) {
            return response()->json([
                'message' => 'Mascotas encontrados',
                'mascotas' => $perros
            ], 200);
        } else {
            return response()->json([
                'message' => 'Mascotas no encontrados'
            ], 404);
        }
    }
    catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al obtener las mascotas',
            'error' => $e->getMessage()
        ], 500);
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

       //Ruta Lista
       public function crearGps(Request $request)
       {
           $validator = Validator::make(
               $request->all(),
               [
                   'device_id' => 'required|string|max:50|unique:gps',
                   'perro_id' => 'required|int',
               ],
               [
                   'device_id.required' => 'El device_id es requerido',
                   'perro_id.required' => 'El perro_id es requerido',
               ]
           );
   
           if ($validator->fails()) {
               return response()->json($validator->errors(), 400);
           }
   
           $gps = Gps::create([
               'device_id' => $request->device_id,
               'perro_id' => $request->perro_id,
               'fecha_inicio' => //formato de fecha
                date('Y-m-d'),
               'created_at' => //formato de fecha y hora
                date('Y-m-d H:i:s')

           ]);
   
           $gps->save();
   
           if ($gps->save()) {
               return response()->json([
                   'message' => 'GPS creado correctamente',
                   'gps' => $gps
               ], 201);
           } else {
               return response()->json([
                   'message' => 'Error al crear el GPS'
               ], 400);
           }
       }
   
   //Ruta Lista
       public function mostrarGpsPerro($id)
       {
           $gps = Gps::where('perro_id', $id)->get();
   
           if ($gps->count() > 0) {
               return response()->json([
                   'message' => 'GPS encontrado',
                   'gps' => $gps
               ], 200);
           } else {
               return response()->json([
                   'message' => 'GPS no encontrado'
               ], 404);
           }
       }

       public function mostrarGPSDeviceID($id)
       {
           $gps = Gps::find($id);
   
           if ($gps->count() > 0) {
               return response()->json([
                   'message' => 'GPS encontrado',
                   'gps' => $gps
               ], 200);
           } else {
               return response()->json([
                   'message' => 'GPS no encontrado'
               ], 404);
           }
       }
       
   
   //Ruta Lista
   public function actualizarGps(Request $request, $id)
   {
       $gps = Gps::find($id);
   
       if (!$gps) {
           return response()->json([
               'message' => 'GPS no encontrado'
           ], 404);
       }
   
       $validator = Validator::make(
           $request->all(),
           [
               'device_id' => 'string|max:50|unique:gps,device_id,' . $gps->id,
               'perro_id' => 'sometimes|int',
           ],
           [
               'device_id.required' => 'El device_id es requerido',
               'perro_id.required' => 'El perro_id es requerido',
           ]
       );
   
       if ($validator->fails()) {
           return response()->json($validator->errors(), 400);
       }
   
       $gps->device_id = $request->device_id;
   
       // Actualizar perro_id solo si se envía en la solicitud
       if ($request->has('perro_id')) {
           $gps->perro_id = $request->perro_id;
       }
   
       if ($gps->save()) {
           return response()->json([
               'message' => 'GPS actualizado correctamente',
               'gps' => $gps
           ], 200);
       } else {
           return response()->json([
               'message' => 'Error al actualizar el GPS'
           ], 400);
       }
   }
      //Ruta Lista
       public function crearZona(Request $request)
       {
           $validator = Validator::make(
               $request->all(),
               [
                   'nombre' => 'required|string|max:50',
                   'latitud' => 'required|string|max:50',
                   'longitud' => 'required|string|max:50',
                   'radio' => 'required|numeric',
                   'gps_id' => 'required|int',
               ],
               [
                   'nombre.required' => 'El nombre es requerido',
                   'latitud.required' => 'La latitud es requerida',
                   'longitud.required' => 'La longitud es requerida',
                   'radio.required' => 'El radio es requerido',
                   'gps_id.required' => 'El gps_id es requerido',
               ]
           );
       
           if ($validator->fails()) {
               return response()->json($validator->errors(), 400);
           }
       
           $gps_id = Gps::find($request->gps_id);

           if (!$gps_id) {
               return response()->json([
                   'message' => 'GPS no encontrado'
               ], 404);
           }

           $zona = Zona::create([
               'nombre' => $request->nombre,
               'latitud' => $request->latitud,
               'longitud' => $request->longitud,
               'radio' => $request->radio,
               'gps_id' => $request->gps_id,
           ]);
       
           if ($zona->save()) {
               return response()->json([
                   'message' => 'Zona creada correctamente',
                   'zona' => $zona
               ], 201);
           } else {
               return response()->json([
                   'message' => 'Error al crear la zona'
               ], 400);
           }
       }
       
   
   
   //Ruta Lista
       public function mostrarZonaGps($id)
       {
           $zona = Zona::where('gps_id', $id)->get();
   
           if ($zona->count() > 0) {
               return response()->json([
                   'message' => 'Zona encontrada',
                   'zona' => $zona
               ], 200);
           } else {
               return response()->json([
                   'message' => 'Zona no encontrada'
               ], 404);
           }
       }
   
   //Ruta Lista
   public function actualizarZonaGps(Request $request, $id)
   {
       $zona = Zona::find($id);
   
       if (!$zona) {
           return response()->json([
               'message' => 'Zona no encontrada'
           ], 404);
       }
   
       $validator = Validator::make(
           $request->all(),
           [
               'nombre' => 'required|string|max:50',
               'latitud' => 'required|string|max:50',
               'longitud' => 'required|string|max:50',
               'radio' => 'required|numeric',
               'gps_id' => 'required|int',
           ],
           [
               'nombre.required' => 'El nombre es requerido',
               'latitud.required' => 'La latitud es requerida',
               'longitud.required' => 'La longitud es requerida',
               'radio.required' => 'El radio es requerido',
               'gps_id.required' => 'El gps_id es requerido',
           ]
       );
   
       if ($validator->fails()) {
           return response()->json($validator->errors(), 400);
       }
   
       $zona->nombre = $request->nombre;
       $zona->latitud = $request->latitud;
       $zona->longitud = $request->longitud;
       $zona->radio = $request->radio;
       $zona->gps_id = $request->gps_id;
   
       if ($zona->save()) {
           return response()->json([
               'message' => 'Zona actualizada correctamente',
               'zona' => $zona
           ], 200);
       } else {
           return response()->json([
               'message' => 'Error al actualizar la zona'
           ], 400);
       }
   }

   public function eliminarZonasGps($id)
   {
       $zona = Zona::find($id);

       if ($zona) {
           $zona->delete();

           return response()->json([
               'message' => 'Zona eliminada',
               'zona' => $zona
           ], 200);
       } else {
           return response()->json([
               'message' => 'Zona no encontrada'
           ], 404);
       }
   }
   
   
   public function generatePDF(Request $request,$id)
    {
        

        $perro = User::Select(
            'users.id',
            'users.nombre',
            'users.apellido_paterno',
            'users.telefono',
            'users.email',
            
            'users.direccion',
            'users.ciudad',
            'users.estado',
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

        if ($perro) {

        $dompdf = new Dompdf();

        // Genera el HTML desde una vista de Laravel
        $html = view('certificado', compact('perro'))->render();

        // Carga el HTML en Dompdf
        $dompdf->loadHtml($html);

        // Opcional: Ajusta el tamaño del papel y la orientación horizontal
        $dompdf->setPaper('A4', 'landscape');
        // Renderiza el PDF
        $dompdf->render();

        // Envía el archivo PDF para su descarga al navegador
        return $dompdf->stream('invoice.pdf');
        } else {
            return response()->json([
                'message' => 'Mascota no encontrado'
            ], 404);
        }


    }

    public function mostrarGPSDeviceIDS()
    {
        $gps = Gps::Select(
                'gps.id',
                'gps.device_id',
                'gps.perro_id',
                'gps.fecha_inicio',
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
                'razas.nombre as raza',
                'users.nombre as usuario_nombre',
                'users.apellido_paterno as usuario_apellido_paterno',
                'users.telefono as usuario_telefono'
            )
            ->join('perros', 'gps.perro_id', '=', 'perros.id')
            ->join('razas', 'perros.id_raza', '=', 'razas.id')
            ->join('users', 'perros.user_id', '=', 'users.id')
            ->get();
    
        if ($gps->count() > 0) {
            return response()->json([
                'message' => 'GPS encontrados',
                'gps' => $gps
            ], 200);
        } else {
            return response()->json([
                'message' => 'GPS no encontrados'
            ], 404);
        }
    }
}
