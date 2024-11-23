<?php
namespace Tests\Feature;
use App\Models\Perro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PerroControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_dog_successfully()
    {
        // Crear un usuario para la autenticación
        $user = User::factory()->create();

        // Datos del perro
        $data = [
            'nombre' => 'Max',
            'color' => 'Negro',
            'edad' => 3,
            'sexo' => 'Macho',
            'peso' => 10.5,
            'tamaño' => 'Mediano',
            'altura' => 60,
            'estatus' => 1,
            'esterilizado' => 'Si',
            'fecha_nacimiento' => '2021-01-01',
            'id_raza' => 1,
        ];

        // Peticion POST
        $response = $this->actingAs($user)->postJson(route('perros.store'), $data);

        // Verificar Respuesta sea status 201
        $response->assertStatus(201);

        // Verificar que el perro fue creado en la base de datos
        $this->assertDatabaseHas('perros', [
            'nombre' => 'Max',
            'color' => 'Negro',
        ]);

        // Verificar que la respuesta contiene el mensaje esperado
        $response->assertJson([
            'message' => 'Perro creado correctamente',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_for_creating_a_dog()
    {
        // Crear un usuario para la autenticación
        $user = User::factory()->create();

        // Datos incompletos
        $data = [
            'nombre' => '',
            'color' => 'Negro',
            'edad' => 3,
            'sexo' => 'Macho',
            'peso' => 10.5,
            'tamaño' => 'Mediano',
            'altura' => 60,
            'estatus' => 1,
            'esterilizado' => 'Si',
            'fecha_nacimiento' => '2021-01-01',
            'id_raza' => 1,
        ];

        // Realiza la petición POST con datos erroneos
        $response = $this->actingAs($user)->postJson(route('perros.store'), $data);

        // Verificar que la respuesta sea un 400 
        $response->assertStatus(400);

        $response->assertJsonValidationErrors(['nombre']);
    }

}
