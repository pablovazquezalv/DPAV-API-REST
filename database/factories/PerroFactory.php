<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Raza;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perro>
 */
class PerroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName, // Nombre aleatorio
            'color' => $this->faker->safeColorName, // Color aleatorio
            'edad' => $this->faker->numberBetween(1, 15), // Edad entre 1 y 15
            'sexo' => $this->faker->randomElement(['Macho', 'Hembra']), // Sexo aleatorio
            'peso' => $this->faker->randomFloat(1, 2, 50), // Peso entre 2 y 50 kg
            'tamaño' => $this->faker->randomElement(['Pequeño', 'Mediano', 'Grande']), // Tamaño
            'estatus' => $this->faker->boolean ? 1 : 0, // Activo o inactivo
            'fecha_nacimiento' => $this->faker->date('Y-m-d', 'now'), // Fecha de nacimiento
            'id_raza' => Raza::factory(), // Relación con la tabla razas
        ];
    }
}
