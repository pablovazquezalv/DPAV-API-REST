<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Perro;
use App\Models\Raza;

class PerroControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_perro()
    {
        // Crear una raza para asociarla al perro
        $raza = Raza::factory()->create();

        // Datos de ejemplo para un nuevo perro
        $data = [
            'nombre' => 'Fido',
            'color' => 'Marrón',
            'edad' => '3',
            'sexo' => 'Macho',
            'peso' => '15',
            'tamaño' => 'Mediano',
            'fecha_nacimiento' => '2020-01-01',
            'id_raza' => $raza->id,
        ];

        // Hacer la solicitud
        $response = $this->post('/crearPerro', $data);

        // Comprobar que se creó el perro
        $response->assertStatus(200);
        $this->assertDatabaseHas('perros', [
            'nombre' => 'Fido',
        ]);
    }

    public function test_actualizar_perro()
    {
        // Crear un perro
        $perro = Perro::factory()->create();

        // Datos de ejemplo para actualizar
        $data = [
            'nombre' => 'Fido Actualizado',
            'color' => $perro->color,
            'edad' => $perro->edad,
            'sexo' => $perro->sexo,
            'peso' => $perro->peso,
            'tamaño' => $perro->tamaño,
            'fecha_nacimiento' => $perro->fecha_nacimiento,
            'id_raza' => $perro->id_raza,
            'estatus' => $perro->estatus,
        ];

        
        // Hacer la solicitud
        $response = $this->put("/perros/{$perro->id}", $data);

        // Comprobar que se actualizó el perro
        $response->assertStatus(200);
        $this->assertDatabaseHas('perros', [
            'id' => $perro->id,
            'nombre' => 'Fido Actualizado',
        ]);
    }

    public function test_eliminar_perro()
    {
        // Crear un perro
        $perro = Perro::factory()->create();

        // Hacer la solicitud
        $response = $this->delete("/perros/{$perro->id}");

        // Comprobar que se eliminó el perro
        $response->assertStatus(302); // Redirección
        $this->assertDatabaseMissing('perros', [
            'id' => $perro->id,
        ]);
    }

    public function test_mostrar_perros()
    {
        // Crear algunos perros
        Perro::factory()->count(1)->create();

        // Hacer la solicitud
        $response = $this->get('/perros');

        // Comprobar que los perros se obtuvieron correctamente
        $response->assertStatus(200);
    }
}
