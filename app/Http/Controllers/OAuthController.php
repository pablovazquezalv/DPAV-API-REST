<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OAuthController extends Controller
{
    private static $authorizationCode = '';

    public function handleAuthorization(Request $request)
    {
        $code = $request->query('code');
        if ($code) {
            self::$authorizationCode = $code;
            \Log::info("Código de autorización recibido: {$code}");
            return response("Código de autorización recibido: {$code}");
        } else {
            return response('No se recibió ningún código de autorización.', 400);
        }
    }

    public function getCode()
    {
        if (self::$authorizationCode) {
            $code = self::$authorizationCode;
            self::$authorizationCode = ''; 
            return response()->json(['code' => $code]);
        } else {
            return response('No hay código de autorización disponible.', 400);
        }
    }
}
