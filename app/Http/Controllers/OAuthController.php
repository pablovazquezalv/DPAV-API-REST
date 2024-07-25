<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OAuthController extends Controller
{
    public function handleAuthorization(Request $request)
    {
        $code = $request->query('code');
        if ($code) {
            DB::table('authorization_codes')->insert(['code' => $code]);
            Log::info("Código de autorización recibido y almacenado: {$code}");
            return response("Código de autorización recibido: {$code}");
        } else {
            Log::error("No se recibió ningún código de autorización.");
            return response('No se recibió ningún código de autorización.', 400);
        }
    }

    public function getCode()
    {
        $record = DB::table('authorization_codes')->orderBy('created_at', 'desc')->first();
        $code = $record ? $record->code : null;

        Log::info("Entrando en getCode, código almacenado: {$code}");
        if ($code) {
            DB::table('authorization_codes')->where('id', $record->id)->delete(); 
            Log::info("Enviando código de autorización: {$code}");
            return response()->json(['code' => $code]);
        } else {
            Log::error("No hay código de autorización disponible");
            return response('No hay código de autorización disponible.', 400);
        }
    }
}
