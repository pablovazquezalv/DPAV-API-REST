<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para registrar un usuario exitosamente.
     */
    public function test_register_user_successfully()
    {
        $response = $this->postJson('/api/register', [ // Enviar solicitud 
            'nombre' => 'Juan',
            'apellido_paterno' => 'Pérez',
            'telefono' => '1234567890',
            'email' => 'juan.perez@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200) // Confirmar status 200
                 ->assertJson([
                     'message' => 'Usuario registrado', // Confirmar mensaje de éxito
                 ]);

        $this->assertDatabaseHas('users', [ // Verificar base de datos
            'email' => 'juan.perez@gmail.com',
        ]);
    }

    /**
     * Verificar que un usuario puede iniciar sesión correctamente.
     */
    public function test_user_login_successfully()
    {
        $user = \App\Models\User::factory()->create([ // Crear un usuario en la base de datos
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'activo' => 1,
        ]);

        $response = $this->postJson('/api/login', [ // Enviar solicitud de login
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200) // Confirmar que la respuesta sea 200
                 ->assertJsonStructure([
                     'message', // Confirmar estructura JSON esperada
                     'user',
                     'token',
                 ]);
    }

    /**
     * Validar que un usuario no puede iniciar sesión con credenciales incorrectas.
     */
    public function test_logout_successfully()
    {
        $user = \App\Models\User::factory()->create(); // Crear un usuario
        $token = $user->createToken('test-token')->plainTextToken; // Generar un token para el usuario

        $response = $this->withHeaders([ // Incluir el token en el header 
            'Authorization' => "Bearer $token",
        ])->postJson('/api/logout'); // Enviar solicitud de logout

        $response->assertStatus(200) // Confirmar respuesta 200
                 ->assertSee('Sesión cerrada'); // Verificar mensaje de éxito
    }

    /**
     * Verificar los validacion de campos al registrar un usuario.
     */
    public function test_register_user_validation_error()
    {
        $response = $this->postJson('/api/register', [ // Enviar solicitud con datos inválidos
            'nombre' => '',
            'email' => 'not-an-email',
            'password' => '123',
        ]);

        $response->assertStatus(400) // Confirmar error de validación (código 400)
                 ->assertJsonValidationErrors(['nombre', 'email', 'password']); // Verificar los campos con errores
    }
}
