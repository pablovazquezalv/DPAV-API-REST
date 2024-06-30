<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage; // Add this import statement
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ImagenController extends Controller
{
    public function upload(Request $request)
    {
        // Validar la solicitud

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $ruta = $request->file('image')->store('fotos', 's3');

        $url = Storage::disk('s3')->url($ruta);

        return response()->json([
            'message' => 'Imagen subida exitosamente',
            'url' => $url,
        ], 200);
    
        // Retornar respuesta
       
    }
}
