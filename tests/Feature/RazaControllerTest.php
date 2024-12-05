<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Raza;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RazaControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Prueba para mostrar todas las razas habilitadas.
     */
    public function testMostrarRazasHabilitadas()
    {
        // Crear razas de prueba
        Raza::factory()->create(['estado' => 1]);
        Raza::factory()->create(['estado' => 0]);

        $response = $this->getJson('/razas/habilitadas');

        $response->assertStatus(200);
        $response->assertJsonCount(1); // Solo debe haber 1 raza habilitada
    }

    /**
     * Prueba para mostrar todas las razas inhabilitadas.
     */
    public function testMostrarRazasInhabilitadas()
    {
        // Crear razas de prueba
        Raza::factory()->create(['estado' => 1]);
        Raza::factory()->create(['estado' => 0]);

        $response = $this->getJson('/razas/inhabilitadas');

        $response->assertStatus(200);
        $response->assertJsonCount(1); // Solo debe haber 1 raza inhabilitada
    }

    /**
     * Prueba para mostrar una raza por ID.
     */
    public function testMostrarRaza()
    {
        $raza = Raza::factory()->create();

        $response = $this->getJson("/razas/{$raza->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $raza->id,
            'nombre' => $raza->nombre,
        ]);
    }

    /**
     * Prueba para manejar el caso de raza no encontrada.
     */
    public function testMostrarRazaNoEncontrada()
    {
        $response = $this->getJson('/razas/999');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'No se encontró la raza']);
    }

    /**
     * Prueba para crear una nueva raza.
     */
    public function testCrearRaza()
    {
        $data = ['nombre' => 'Nueva Raza'];

        $response = $this->postJson('/razas', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('razas', ['nombre' => 'Nueva Raza']);
    }

  

    /**
     * Prueba para actualizar una raza existente.
     */
    public function testActualizarRaza()
    {
        $raza = Raza::factory()->create(['nombre' => 'Raza Original']);

        $data = ['nombre' => 'Raza Actualizada'];

        $response = $this->putJson("/razas/{$raza->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('razas', ['nombre' => 'Raza Actualizada']);
    }

   
    
    /**
     * Prueba para eliminar una raza.
     */
    public function testEliminarRaza()
    {
        $raza = Raza::factory()->create();

        $response = $this->delete("/razas/{$raza->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('razas', ['id' => $raza->id]);
    }
}
