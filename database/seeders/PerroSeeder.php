<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perro;
use App\Models\User;
use App\Models\Raza;

class PerroSeeder extends Seeder
{
    public function run()
    {
        $usuarios = User::all();
        $razas = Raza::all();

        foreach ($usuarios as $user) {
            foreach ($razas as $raza) {
                Perro::create([
                    'nombre' => 'Perro de ' . $user->nombre,
                    'color' => 'Marrón',
                    'edad' => rand(1, 10),
                    'sexo' => rand(0, 1) ? 'Macho' : 'Hembra',
                    'peso' => rand(5, 30),
                    'tamaño' => ['Pequeño', 'Mediano', 'Grande'][rand(0, 2)],
                    'altura' => rand(20, 60),
                    'estatus' => 1,
                    'esterilizado' => rand(0, 1) ? 'Si' : 'No',
                    'fecha_nacimiento' => now()->subYears(rand(1, 10))->toDateString(),
                    'id_raza' => $raza->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
