<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PerroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('perros')->insert([
            'nombre' => 'Firulais',
            'distintivo' => 'Firu',
            'sexo' => 'M',
            'peso' => 50.5,
            'tamaño' => 'grande',
            'estatus' => 1,
            'esterilizado' => 'si',
            'fecha_nacimiento' => '2021-06-03',
            'imagen' => 'https://www.google.com',
            'chip' => '123456789',
            'tipo' => 'venta',
            'id_raza' => 1,
            'user_id' => 1,
        ]);

        DB::table('perros')->insert([
            'nombre' => 'peso pluma',
            'distintivo' => 'Firu',
            'sexo' => 'M',
            'peso' => 50.5,
            'tamaño' => 'grande',
            'estatus' => 1,
            'esterilizado' => 'si',
            'fecha_nacimiento' => '2021-06-03',
            'imagen' => 'https://www.google.com',
            'chip' => '123456789',
            'tipo' => 'venta',
            'id_raza' => 1,
            'user_id' => 1,
        ]);

        DB::table('perros')->insert([
            'nombre' => 'natanel',
            'distintivo' => 'Firu',
            'sexo' => 'M',
            'peso' => 50.5,
            'tamaño' => 'grande',
            'estatus' => 1,
            'esterilizado' => 'si',
            'fecha_nacimiento' => '2021-06-03',
            'imagen' => 'https://www.google.com',
            'chip' => '123456789',
            'tipo' => 'venta',
            'id_raza' => 1,
            'user_id' => 1,
        ]);



        DB::table('perros')->insert([
            'nombre' => 'Garfield',
            'distintivo' => 'Garfi',
            'sexo' => 'M',
            'peso' => 15.2,
            'tamaño' => 'mediano',
            'estatus' => 1,
            'esterilizado' => 'si',
            'fecha_nacimiento' => '2020-05-01',
            'imagen' => 'https://www.google.com',
            'chip' => '987654321',
            'tipo' => 'cria',
            'id_raza' => 2,
            'user_id' => 2,
        ]);

        


        

    }
}
