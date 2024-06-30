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

        $request->validate([
            'image' => 'required',
        ]);

        // Obtener el archivo de la solicitud
        $image = $request->file('image');

        // Generar un nombre único para la imagen
        $imageName = time() . '.' . $image->getClientOriginalExtension();

        // Subir la imagen a S3
        $path = Storage::disk('s3')->putFileAs('images', $image, $imageName);

        // Obtener la URL de la imagen
        $imageUrl = Storage::disk('s3')->url($path);

        // Retornar respuesta
        return response()->json([
            'message' => 'Imagen subida exitosamente',
            'url' => $imageUrl
        ], 200);
    }
    //respuesta
       
    
}
