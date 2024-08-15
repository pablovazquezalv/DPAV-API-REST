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
use App\Models\Estado;
use Illuminate\Support\Facades\URL;
use App\Mail\enviarCodigoSesion;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{


    //FUNCION PARA MOSTRAR USUARIO
    public function mostrarUsuario()
    {

        $user = request()->user();

        $user = User::find($user->id);

        if($user)
        {
            return response()->json($user, 200);
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }
    }


    //FUNCION PARA EDITAR TELEFONO DE USUARIO
    public function editarTelefonoUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telefono' => 'required|string|max:10',
        ],
        [
            'telefono.required' => 'El teléfono es requerido',
            'telefono.max' => 'El teléfono debe tener 10 dígitos',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = request()->user();

        //enviar codigo de verificación
        $this->enviarCodigo(new Request(['id' => $user->id]));
        
        
        $user = User::find($user->id);

        if($user)
        {
            return response()->json([
                'message' => 'Código enviado',
                'telefono' => $request->telefono
            ]);
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }

    }

     //FUNCION PARA EDITAR EL TELEFONO DE USUARIO
     public function verificarTelefono(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'telefono' => 'required|string|max:10',
             'codigo' => 'required|string|max:6',
         ],
         [
             'telefono.required' => 'El teléfono es requerido',
             'codigo.required' => 'El código es requerido',
             'telefono.max' => 'El teléfono debe tener 10 dígitos',
             'codigo.max' => 'El código debe tener 6 caracteres',
         ]);
 
             if ($validator->fails()) {
                 return response()->json($validator->errors(), 400);
             }
 
             $user = request()->user();
             $user = User::where('email', $user->email)->first();
 
             
             if($user->codigo == $request->codigo)
             {
                # $user->codigo = null;
 
                 $user->telefono = $request->telefono;
                 $user->codigo = null;
 
              
                 $user->save();
 
                 return response()->json([
                     'message' => 'Código correcto',
                     'nuevo telefono' => $request->telefono
                 ]);
             }
             else
             {
                 return response()->json([
                     'message' => 'Código incorrecto',
                     
                 ], 400);
             }
     }

    //FUNCION PARA EDITAR DIRECCION DE USUARIO
    public function editarDireccionUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'direccion' => 'sometimes|string|max:250',
            'colonia' => 'sometimes|string|max:50',
            'ciudad' => 'sometimes|string|max:50',
            'codigo_postal' => 'sometimes|string|max:5',
            'estado' => 'sometimes|string|max:50',
        ],

        [
            'direccion.required' => 'La dirección es requerida',
            'direccion.max' => 'La dirección debe tener 255 caracteres',
            'ciudad.required' => 'La ciudad es requerida',
            'ciudad.max' => 'La ciudad debe tener 50 caracteres',
            'codigo_postal.required' => 'El código postal es requerido',
            'codigo_postal.max' => 'El código postal debe tener 5 caracteres',
            'estado.required' => 'El estado es requerido',
            'estado.max' => 'El estado debe tener 50 caracteres',
            'colonia.required' => 'La colonia es requerida',
            'colonia.max' => 'La colonia debe tener 50 caracteres',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = request()->user();

        $user = User::find($user->id);

        

        if($user)
        {
            $user->direccion = $request->direccion;
            $user->ciudad = $request->ciudad;
            $user->codigo_postal = $request->codigo_postal;
            $user->estado = $request->estado;
            $user->colonia = $request->colonia;

            #$user->ativo = $request->ativo;

            $user->save();

            if($user->save())
            {
                return response()->json($user, 200);
            }
            else
            {
                return response()->json('Error al actualizar dirección', 400);
            }
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }
    }

   
    //FUNCION PARA EDITAR EMAIL DE USUARIO
    public function editarEmailUsuario(Request $request)
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

        $user = request()->user();

        $user = User::find($user->id);

        if($user)
        {
            $user->email = $request->email;
            $user->save();

            return response()->json($user, 200);
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }
    }


    // REGISTRO DE USUARIOS

    //FUNCION PARA REGISTRAR USUARIO
    public function registrarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|min:2',
            'apellido_paterno' => 'required|string|max:50',
            'telefono' => 'required|string|max:10|min:10|unique:users',
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
            'telefono.min' => 'El teléfono debe tener 10 dígitos',
            'telefono.unique' => 'El teléfono ya está en uso',
          
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

    //FUNCION PARA LOGIN DE USUARIO
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

    //FUNCION PARA LOGIN DE USUARIO DESDE SMARTWATCH
    public function loginSwarthWatch(Request $request)
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
                if($user->activo == 0)
                {
                    return response()->json([
                        'message' => 'Usuario inactivo',
                       
                    
                    ], 400);
                }
                else
                {
                    $user->codigo = rand(100000, 999999);
                    $user->codigo_expiration = now()->addMinutes(5);

                    $user->save();
    
                    $codigoResponse = $this->enviarCodigo(new Request(['id' => $user->id]));


                    // Verificar si hubo un error en el envío del código
                    if ($codigoResponse->getStatusCode() == 200) 
                    {
                        return response()->json([
                            'message' => 'Código enviado',
                            'codigo' => $user->codigo
                        ]);
                    } else 
                    {
                        return $codigoResponse; // Retornar la respuesta del error
                    }
                    
                }
            }
            else
            {
                return response()->json('Usuario no encontrado.', 500);
            }
    }


    //FUNCION PARA VERIFICAR CODIGO DE SESION DE USUARIO DESDE SMARTWATCH
    public function loginVerificarCodigoSmartWatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'codigo' => 'required|max:6',
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

           
            $user = User::where('codigo', $request->codigo)->first();
            if ($user) {
                // Verificar si el código ha caducado
               
                    $token = $user->createToken('token')->plainTextToken;
                    return response()->json([
                        'message' => 'Usuario autenticado',
                        'user' => $user,
                        'token' => $token
                    ]);
                
              
            } else {
                return response()->json([
                    'message' => 'Código incorrecto',
                ], 400);
            }
    }

    //FUNCION PARA CERRAR SESION
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json('Sesión cerrada', 200);
    }

    //FUNCION PARA VERIFICAR TOKEN
    public function verificarToken(Request $request)
    {
        $user = $request->user();
        
        if($user)
        {
            return response()->json([
                'message' => 'Token válido',
                'id' => $user->id,
                'role' => $user->role,
                'status' => $user->status,
            ], 200);
        }
    }

    //FUNCION PARA VERIFICAR CODIGO DE ACTIVACION
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

    //FUNCION PARA OLVIDAR CONTRASEÑA
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
            $url = URL::temporarySignedRoute('restablecerContraseñaView', now()->addMinutes(5), ['id' => $user->id]);

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

    //FUNCION PARA RESTABLECER CONTRASEÑA
    public function restablecerContraseña(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ],
        [
           
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',

            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();

        }

        $user = User::where('email', $request->email)->first();


        if($user)
        {
            $user->password = Hash::make($request->password);
            $user->save();

            if($user->save())
            {
                return response()->view('contraseña-cambiada', ['user' => $user]);
            }
            else
            {

                //regresar la misma  vi,sta en la que se encontraba
                return redirect()->back()->withErrors($validator)->withInput();

            }
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }

    }

    //FUNCION PARA MOSTRAR LA VISTA DE RESTABLECER CONTRASEÑA
    public function restablecerContraseñaView(Request $request)
    {
        $user = User::find($request->id);


        return view('restablecer-contraseña', ['user' => $user]);
    }
  
    //FUNCION PARA MOSTRAR LA VISTA DE CONTRASEÑA RESTABLECIDA
    public function contraseñaRestablecidaView(Request $request)
    {
        $user = User::find($request->id);

        return view('contraseña-cambiada', ['user' => $user]);
    }

    //FUNCION PARA ENVIAR CODIGO DE SESION Y REENVIARLO
    public function enviarCodigoCuenta(Request $request)
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
            $user->save();


             // Enviar código SMS llamando a la función enviarCodigo
            $codigoResponse = $this->enviarCodigo(new Request(['id' => $user->id]));

            // Verificar si hubo un error en el envío del código
            if ($codigoResponse->getStatusCode() == 200) 
            {
                return response()->json([
                    'message' => 'Código enviado'
                ]);
            } else 
            {
                return $codigoResponse; // Retornar la respuesta del error
            }
        }
        else
        {
            return response()->json('Usuario no encontrado', 400);
        }
    }


    
    //RUTAS FIRMADAS - SERVICIOS

    //FUNCION PARA ENVIAR CODIGO DE SESION EN REGISTRO
    public function enviarSMS(Request $request)
    {
        $user = User::find($request->id);

        if($user->verification_code_sent_at != null)
        {
            return response()->json('Código ya enviado', 400);
        }
       
        $response = Http::withHeaders([

            'Authorization' => 'App ae6f077ec349231e89761c8c54350ab6-e558221a-0ade-4c70-a4c1-cbb04196ef64',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post('https://e1qzvq.api.infobip.com/sms/2/text/advanced', [
            'messages' => [
                [
                   'destinations' => [
                       [
                           'to' => '528712736050'
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


    //FUNCION PARA ENVIAR CODIGO DE SESION O REENVIARLO
    public function enviarCodigo(Request $request)
    {

       if(request()->has('email'))
         {
              $user = User::where('email', request()->email)->first();
         }
         else
         {
             $user = User::where('id', $request->id)->first();
         }


        $response = Http::withHeaders([
            'Authorization' => 'App ae6f077ec349231e89761c8c54350ab6-e558221a-0ade-4c70-a4c1-cbb04196ef64',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post('https://e1qzvq.api.infobip.com/sms/2/text/advanced', [
            'messages' => [
                [
                    'destinations' => [
                        [
                            'to' => '528712736050'
                        ]
                    ],
                    'from' => 'InfoSMS',
                    'text' => 'Hola nos comunicamos de DPAV tu código es: ' . $user->codigo
                ]
            ]
        ]);
    
        if ($response->status() == 200) {
            $user->verification_code_sent_at = now();
            $user->save();
            return response()->json('Código enviado exitosamente por SMS', 200);
        } else {
            return response()->json('Error al enviar SMS', 400);
        }
    }
    
}


