<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage; // Add this import statement
use Illuminate\Http\Request;

class ImagenController extends Controller
{
    public function upload(Request $request)
    {
        // Validar la solicitud

        
        $request->validate([
            'image' => 'required|image|max:2048', // Puedes ajustar las reglas de validación según tus necesidades
        ]);

        $router = Storage::disk('s3')->put('fotos',$request->image);// Usar el disco 'public' para almacenar las imágenes

        $url = Storage::disk('s3')->url($router); // Obtener la URL de la imagen

        return response()->json([
            'message' => 'Imagen subida exitosamente',
            'url' => $url,
        ], 200);
        // Retornar respuesta
       
    }
}
