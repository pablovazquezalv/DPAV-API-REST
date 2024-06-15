<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CertificadoController extends Controller
{
    
    public function crearCertificado(Request $request)
    {
       $validator = Validator::make($request->all(),
       [
           'perro_id' => 'required|int',
           'fecha' => 'required|date',
       ],
       [
           'perro_id.required' => 'El perro es requerido',
           'fecha.required' => 'La fecha es requerida',
       ]);

         if ($validator->fails()) {
              return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
              ], 400);
         }

            $certificado = Certificado::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Certificado creado correctamente',
                'data' => $certificado
            ], 201);
            
    }

}
