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

        if(!$request->hasFile('image')) {
            return response()->json([
                'message' => 'No se ha enviado ninguna imagen',
            ], 400);
        }

        $file = $request->file('image');

        $route = Storage::disk('s3')->put('images', $file);

        // Obtener la URL de la imagen
        $imageUrl = Storage::disk('s3')->url($route);
    
        // Retornar respuesta
        return response()->json([
            'message' => 'Imagen subida exitosamente',
            'url' => $imageUrl
        ], 200);
    }
    //respuesta
       
    
}
