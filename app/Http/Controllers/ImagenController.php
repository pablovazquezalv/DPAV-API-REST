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


        // Subir la imagen al disco 'public'
        

        $router = Storage::disk('s3')->put('fotos',$request->image);// Usar el disco 'public' para almacenar las imágenes

        $url = Storage::disk('s3')->url($router); // Obtener la URL de la imagen

        return response()->json([
            'message' => 'Imagen subida exitosamente',
            'url' => $url,
        ], 200);
        // Retornar respuesta
       
    }
}
