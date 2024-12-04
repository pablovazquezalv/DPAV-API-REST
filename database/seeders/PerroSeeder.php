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
                    'nombre' => 'Perro de ' . $user->name,
                    'color' => 'negro',
                    'sexo'=>'Macho',
                    'peso'=> '30',
                    'estatus'=>1,
                    'tamaño'=> 'Pequeño',
                    'fecha_nacimiento'=>'05/10/23',
                    'edad'=> 4,
                    'id_raza'=> 1,
                    'user_id'=>1
                ]);
            }
        }
    }
}
