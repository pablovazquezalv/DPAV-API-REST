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
        /*
        * Crear usuario
        */
        public function registerUser(Request $request)
        {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'telefono' => 'required|string|max:10',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ],
        //mensajes de error
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

        /*
        * Validar errores
        */
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }


        /*
        * Crear usuario
        */
        $user = User::create([
            'nombre' => $request->nombre,
            'usuario' => explode('@', $request->email)[0],
            'apellido_paterno' => $request->apellido_paterno,
            'telefono' => $request->telefono,
            'email' => $request->email,

            'password' => Hash::make($request->password),
            'activo' => 1,
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

    /*
    * Iniciar sesión de usuario
    */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ],
        //mensajes de error
        [
            'email.required' => 'El email es requerido',
            'password.required' => 'La contraseña es requerida',
            'email.email' => 'El email no es válido',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',

        ]);

            // Validar errores
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            // Buscar usuario en la base de datos
            $user = User::where('email', $request->email)->first();


            // Verificar si el usuario existe y la contraseña es correcta
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

}