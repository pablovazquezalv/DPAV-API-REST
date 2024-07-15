<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class CitaController extends Controller
{
    //ADMIN
    public function mostrarCitas()
{
    try {
        $citas = Cita::select('citas.id', 'citas.fecha', 'citas.hora', 'citas.motivo', 'citas.estado', 'users.nombre as nombre_usuario', 'users.apellido_paterno as apellido_usuario', 'users.telefono as telefono_usuario')
            ->join('users', 'citas.user_id', '=', 'users.id')
            ->orderBy('citas.fecha', 'desc') // Ordenar por fecha en orden descendente
            ->orderBy('citas.hora', 'desc')  // Ordenar por hora en orden descendente
            ->get();

        // Formatear las fechas y horas
        $citas->transform(function($cita) {
            $cita->fecha = Carbon::parse($cita->fecha)->format('d-m-Y'); // Cambia el formato de la fecha
            $cita->hora = Carbon::parse($cita->hora)->format('H:i'); // Cambia el formato de la hora
            return $cita;
        });

        return response()->json([
            'message' => 'Citas encontradas',
            'citas' => $citas
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al obtener las citas',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
    public  function mostrarCita($id)
    {
        $cita = Cita::find($id);

        if($cita == null)
        {
            return response()->json(['message' => 'No se encontro la cita'], 404);
        }

        return response()->json($cita, 200);
    }


    public function aceptarCitaAdmin($id)
    {
        $cita = Cita::find($id);

        if($cita == null)
        {
            return response()->json(['message' => 'No se encontro la cita'], 404);
        }

        $cita->estado = 'aceptada';

        $cita->save();

        if($cita->save())
        {
            return response()->json([
                'message' => 'Cita aceptada correctamente',
                'cita' => $cita
            ]);
        }
        else
        {
            return response()->json(['message' => 'Error al aceptar la cita'], 400);
        }
    }


    public function cancelarCitaAdmin($id)
    {
        $cita = Cita::find($id);

        if($cita == null)
        {
            return response()->json(['message' => 'No se encontro la cita'], 404);
        }

        $cita->estado = 'cancelada';

        $cita->save();

        if($cita->save())
        {
            return response()->json([
                'message' => 'Cita cancelada correctamente',
                'cita' => $cita
            ]);
        }
        else
        {
            return response()->json(['message' => 'Error al cancelar la cita'], 400);
        }
    }

    public function crearCita(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'required|in:cruce,compra,consulta',
        ], [
            'fecha.required' => 'La fecha es requerida',
            'fecha.date' => 'La fecha debe ser una fecha válida, por ejemplo: 2021-06-04',
            'hora.required' => 'La hora es requerida',
            'hora.date_format' => 'La hora debe estar en el formato HH:mm, por ejemplo: 14:30',
            'motivo.required' => 'El motivo es requerido',
            'motivo.in' => 'El motivo debe ser cruce, compra o consulta'
        ]);
    
        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->isEmpty()) {
                $currentDateTime = Carbon::now()->addMinutes(15);
                $inputDateTime = Carbon::parse($request->input('fecha') . ' ' . $request->input('hora'));
    
                if ($inputDateTime->lessThan($currentDateTime)) {
                    $validator->errors()->add('fecha', 'La fecha y hora no pueden ser anteriores a la fecha y hora actual con 15 minutos de retraso.');
                }
    
                // Validar que la cita no esté entre las 19:00 y las 09:00 del día siguiente
                $inputTime = Carbon::parse($request->input('hora'))->format('H:i');
                $prohibitedStartTime = Carbon::createFromTime(19, 0, 0)->format('H:i'); // 19:00
                $prohibitedEndTime = Carbon::createFromTime(9, 0, 0)->format('H:i'); // 09:00
    
                if (($inputTime >= $prohibitedStartTime && $inputTime <= '23:59') || ($inputTime >= '00:00' && $inputTime < $prohibitedEndTime)) {
                    $validator->errors()->add('hora', 'No se pueden hacer citas entre las 19:00 y las 09:00.');
                }
    
                // Validar que no haya más de dos citas en la misma hora
                $citasEnLaMismaHora = Cita::where('fecha', $request->input('fecha'))
                    ->where('hora', $request->input('hora'))
                    ->count();
    
                $citasEnLaMismaHora += 1; // Sumar la cita actual
                if ($citasEnLaMismaHora > 2) {
                    $validator->errors()->add('hora', 'Ya hay dos citas programadas para esta hora.');
                }
    
                // Validar que la cita no sea más de 2 meses en el futuro
                $fechaLimite = Carbon::now()->addMonths(2);
                if ($inputDateTime->greaterThan($fechaLimite)) {
                    $validator->errors()->add('fecha', 'No se pueden crear citas con más de 2 meses de anticipación.');
                }
            }
        });
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        // Procesar la creación de la cita
        $user = $request->user();
    
        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'codigo' => uniqid(), // Generar un código único para la cita
            'motivo' => $request->motivo,
            'user_id' => $user->id
        ]);
    
        if ($cita) {
            return response()->json($cita, 201);
        } else {
            return response()->json(['message' => 'Error al crear la cita'], 400);
        }
    }
    //CANCELAR CITA USUARIO
    public function cancelarCita($id)
    {
        $cita = Cita::find($id);
    
        if($cita == null)
        {
            return response()->json(['message' => 'No se encontro la cita'], 404);
        }
    
        // Obtén el usuario actualmente autenticado
        $user = request()->user();
    
        // Verifica si el usuario autenticado es el que creó la cita
        if ($user->id !== $cita->user_id) {
            return response()->json(['message' => 'No tienes permiso para cancelar esta cita'], 403);
        }
    
        if ($cita->estado === 'cancelada') {
            return response()->json(['message' => 'Esta cita ya ha sido cancelada previamente'], 400);
        }
    
        $cita->estado = 'cancelada';
    
        $cita->save();
    
        if($cita->save())
        {
            return response()->json([
                'message' => 'Cita cancelada correctamente',
                'cita' => $cita
            ]);
        }
        else
        {
            return response()->json(['message' => 'Error al cancelar la cita'], 400);
        }
    }

    public function verMisCitas()
    {
        $user = request()->user();
    
        $citas = Cita::where('user_id', $user->id)->get();
    
        return response()->json($citas);
    }

    
    

}
