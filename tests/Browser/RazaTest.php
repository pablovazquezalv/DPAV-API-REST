<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Raza;

class RazaTest extends DuskTestCase
{
    use DatabaseMigrations; // Asegúrate de usar DatabaseMigrations

    public function testCrearRaza()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/raza')
                ->waitFor('input[name="nombre"]')
                ->type('nombre', 'Nueva Raza')
                ->press('Crear Raza')
                ->assertPathIs('/razas')
                ->assertSee('Raza creada correctamente');
        });
    }

    public function testEditarRaza()
    {
        $this->browse(function (Browser $browser) {
            $raza = \App\Models\Raza::factory()->create([
                'nombre' => 'Raza Original'
            ]);

            $browser->visit("/razas/{$raza->id}/edit")
                ->waitFor('input[name="nombre"]')
                ->type('nombre', 'Raza Editada')
                ->press('Actualizar Raza')
                ->assertPathIs('/razas')
                ->assertSee('Raza actualizada correctamente');
        });
    }

    public function testEliminarRaza()
    {
        $this->browse(function (Browser $browser) {
            // Crear una raza para eliminarla
            $raza = Raza::factory()->create([
                'nombre' => 'Raza Para Eliminarteee'
            ]);

            // Visitar la página del listado de razas
            $browser->visit('/razas')
                ->waitForText('Listado de Razas') // Espera a que cargue la lista
                ->assertSee($raza->nombre) // Asegúrate de que la raza aparezca en la lista
                ->press('Eliminar') // Presiona el botón de eliminar
                ->assertDialogOpened('¿Estás seguro de que deseas eliminar esta raza? Esta acción no se puede deshacer.') // Asegúrate de que aparece el diálogo de confirmación
                ->acceptDialog() // Acepta el diálogo de confirmación
                ->assertPathIs('/razas'); // Verifica que la página se redirija a la lista de razas

            // Verifica que el registro ha sido eliminado de la base de datos
            $this->assertDatabaseMissing('razas', [
                'nombre' => 'Raza Para Eliminarteee',
            ]);
        });
    }
}
