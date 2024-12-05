<?php
namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Raza;

class RazaSystemTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_crear_raza_desde_el_navegador()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/razas/create')
                    ->type('nombre', 'Labrador')
                    ->press('Crear Raza')
                    ->assertSee('Labrador');
        });
    }

    public function test_eliminar_raza_desde_el_navegador()
    {
        // Crea una raza en la base de datos
        $raza = Raza::factory()->create(['nombre' => 'Husky']);

        $this->browse(function (Browser $browser) use ($raza) {
            $browser->visit('/razas')
                    ->click('@delete-button-' . $raza->id) // Usa un identificador único
                    ->acceptDialog()
                    ->assertDontSee('Husky');
        });
    }
}
