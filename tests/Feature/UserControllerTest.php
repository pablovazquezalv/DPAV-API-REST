<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Refresca la base de datos y ejecuta seeders
        Artisan::call('migrate:fresh', ['--seed' => true]);
    }

    public function test_register_user_successfully()
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        $data = [
            'nombre' => 'John',
            'apellido_paterno' => 'Doe',
            'telefono' => '1234567890',
            'email' => 'johndoe@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'activo' => true,
            'role_id' => 1,
        ];

        $response = $this->postJson(route('registrar'), $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'user', 'url']);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);

        Mail::assertSent(\App\Mail\RegisterMail::class, function ($mail) use ($data) {
            return $mail->hasTo($data['email']);
        });
    }

    public function test_register_user_validation_errors()
    {
        $response = $this->postJson(route('registrar'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'nombre',
                'apellido_paterno',
                'telefono',
                'email',
                'password',
            ]);
    }

    public function test_user_login_successfully()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $response = $this->postJson(route('login'), [
            'email' => 'test@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'user', 'token']);
    }

    public function test_user_login_failure()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(400)
            ->assertExactJson(['Usuario o contraseña incorrectos']);
    }

    public function test_user_logout_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('logout'));

        $response->assertStatus(200)
            ->assertExactJson(['Sesión cerrada']);
    }
}
