<?php

namespace App\Http\Controllers;

use App\Mail\OlvideContraseña;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    public function registrarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'telefono' => 'required|string|max:10',
            'ciudad' => 'required|string|max:255',
            'colonia' => 'required|string|max:255',
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:25',
            'estado' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ],
        [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'password.required' => 'La contraseña es requerida',
        //  'email.email' => 'El email no es válido',
        //  'email.unique' => 'El email ya está en uso',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'telefono' => $request->telefono,
            'ciudad' => $request->ciudad,
            'colonia' => $request->colonia,
            'calle' => $request->calle,
            'activo' => 0, // '0' es el estado 'inactivo
            'role_id' => 3, // '1' es el id del rol 'user
            'numero' => $request->numero,
            'codigo'=> rand(100000, 999999), //genera un código aleatorio de 6 dígitos (opcional
            'codigo_postal' => $request->codigo_postal,
            'estado' => $request->estado,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

      
        if($user->save())
        {
            $url = URL::temporarySignedRoute('enviarSMS', now()->addMinutes(5), ['id' => $user->id]);

            Mail::to($user->email)->send(new RegisterMail($user, $url));


            return response()->json([
                'message' => 'Usuario registrado',
                'user' => $user,
                'url' => $url
            
            ]);
        }
        else
        {
            return response()->json('Error al registrar usuario', 400);
        }
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ],
        [
            'email.required' => 'El email es requerido',
            'password.required' => 'La contraseña es requerida',
            'email.email' => 'El email no es válido',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            
        ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::where('email', $request->email)->first();


            if($user && Hash::check($request->password, $user->password))
            {
                if($user->activo == 0)
                {
                    return response()->json('Usuario no verificado', 400);
                }
                else
                {
                    $token = $user->createToken('token')->plainTextToken;
                    return response()->json(['token' => $token], 200);
                }
            }
            else
            {
                return response()->json('Usuario o contraseña incorrectos', 400);
            }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json('Sesión cerrada', 200);
    }

    public function verificarCodigo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'codigo' => 'required|string|max:6',
        ],
        [
            'email.required' => 'El email es requerido',
            'codigo.required' => 'El código es requerido',
            'email.email' => 'El email no es válido',
            'codigo.max' => 'El código debe tener 6 caracteres',
            
        ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::where('email', $request->email)->first();

            
            if($user->codigo == $request->codigo)
            {
                $user->codigo = null;
                $user->activo = 1;
                $user->save();

                return response()->json('Código correcto', 200);
            }
            else
            {
                return response()->json('Código incorrecto', 400);
            }
    }

    public function olvideContraseña(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ],
        [
            'email.required' => 'El email es requerido',
            'email.email' => 'El email no es válido',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if($user)
        {
            $url = URL::temporarySignedRoute('olvideContraseña', now()->addMinutes(5), ['id' => $user->id]);

            Mail::to($user->email)->send(new OlvideContraseña($user, $url));

            return response()->json([
                'message' => 'Correo enviado',
                'user' => $user,
                'url' => $url
            ]);
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }

    }

    public function restablecerContraseña(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ],
        [
            'email.required' => 'El email es requerido',
            'password.required' => 'La contraseña es requerida',
            'email.email' => 'El email no es válido',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if($user)
        {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json('Contraseña restablecida', 200);
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }


    }

    //RUTAS FIRMADAS - SERVICIOS
    public function enviarSMS(Request $request)
    {
        $user = User::find($request->id);

        if($user->verification_code_sent_at != null)
        {
            return response()->json('Código ya enviado', 400);
        }
       
        $response = Http::withHeaders([

            'Authorization' => 'App 16abca2ac5b56ee130ca5c236b16943a-1d3e8a4d-cd81-4bec-96c5-05fb2dbdc14b',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post('https://2v8jjz.api.infobip.com/sms/2/text/advanced', [
            'messages' => [
                [
                   'destinations' => [
                       [
                           'to' => '528718458147'
                           //'to' => $user->telefono
                       ]
                   ],
                   'from' => 'InfoSMS',
                   'text' => 'Tu código de verificación es: '.$user->codigo
                ]
            ]
    
        ]);

        if($response->status() == 200)
        {
            $user->verification_code_sent_at = now();
            $user->email_verified_at = now();
            $user->save();
            return response()->view('email/correo-enviado', ['user' => $user]);
        }
        else
        {
            return response()->json('Error al enviar SMS', 400);
        }
    }

    
}
