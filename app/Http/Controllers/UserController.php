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
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function registrarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|min:2',
            'apellido_paterno' => 'required|string|max:50',
            'telefono' => 'required|string|max:10',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ],
        [
            'nombre.required' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'password.required' => 'La contraseña es requerida',
            'email.email' => 'El email no es válido',
            'email.unique' => 'El email ya está en uso',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'telefono.required' => 'El teléfono es requerido',
            'telefono.max' => 'El teléfono debe tener 10 dígitos',
          
            'apellido_paterno.required' => 'El apellido paterno es requerido',
            'apellido_materno.required' => 'El apellido materno es requerido',
        ]);

        if ($validator->fails()) 
        {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'nombre' => $request->nombre,
            'usuario' => explode('@', $request->email)[0], 
            'apellido_paterno' => $request->apellido_paterno,
            'telefono' => $request->telefono,
            'email' => $request->email,
            
            'password' => Hash::make($request->password),
            'activo' => 0, // '0' es el estado 'inactivo
            'role_id' => 3, // '1' es el id del rol 'user
            'numero' => $request->numero,
            'codigo'=> rand(100000, 999999), //genera un código aleatorio de 6 dígitos (opcional
           
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
                    return response()->json([
                        'message' => 'Usuario inactivo',
                        'user' => $user
                    
                    ], 400);
                }
                else
                {
                    $token = $user->createToken('token')->plainTextToken;
                    return response()->json([
                        'message' => 'Usuario autenticado',
                        'user' => $user,
                        'token' => $token
                    ]);
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
               # $user->codigo = null;
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
            $url = URL::temporarySignedRoute('restablecerContraseña', now()->addMinutes(5), ['id' => $user->id]);

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
            'email.email' => 'El email no es válido',
            'password.required' => 'La contraseña es requerida',
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
