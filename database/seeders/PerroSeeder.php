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
            'sexo' => 'Macho',
            'peso' => 50.5,
            'tamano' => 'Grande',
            'estatus' => true,
            'esterilizado' => 'Si',
            'fecha_nacimiento' => '2021-06-03',
            'imagen' => 'https://proyecto9o.s3.amazonaws.com/images/LF3fYwHcA3CKnjMn5jjmFsjUb9eqbPd34iqR4YUw.jpg',
            'chip' => '123456789',
            'tipo' => 'Venta',
            'id_raza' => 1,
            'user_id' => 1,
        ]);

        DB::table('perros')->insert([
            'nombre' => 'peso pluma',
            'distintivo' => 'Firu',
            'sexo' => 'Macho',
            'peso' => 50.5,
            'tamano' => 'Grande',
            'estatus' => true,
            'esterilizado' => 'Si',
            'fecha_nacimiento' => '2021-06-03',
            'imagen' => 'https://proyecto9o.s3.amazonaws.com/images/LF3fYwHcA3CKnjMn5jjmFsjUb9eqbPd34iqR4YUw.jpg',
            'chip' => '123456789',
            'tipo' => 'Venta',
            'id_raza' => 1,
            'user_id' => 1,
        ]);

        DB::table('perros')->insert([
            'nombre' => 'natanel',
            'distintivo' => 'Firu',
            'sexo' => 'Macho',
            'peso' => 50.5,
            'tamano' => 'Grande',
            'estatus' => true,
            'esterilizado' => 'Si',
            'fecha_nacimiento' => '2021-06-03',
            'imagen' => 'https://proyecto9o.s3.amazonaws.com/images/LF3fYwHcA3CKnjMn5jjmFsjUb9eqbPd34iqR4YUw.jpg',
            'chip' => '123456789',
            'tipo' => 'venta',
            'id_raza' => 1,
            'user_id' => 1,
        ]);



        DB::table('perros')->insert([
            'nombre' => 'Garfield',
            'distintivo' => 'Garfi',
            'sexo' => 'Macho',
            'peso' => 15.2,
            'tamano' => 'Mediano',
            'estatus' => 1,
            'esterilizado' => 'Si',
            'fecha_nacimiento' => '2020-05-01',
            'imagen' => 'https://proyecto9o.s3.amazonaws.com/images/LF3fYwHcA3CKnjMn5jjmFsjUb9eqbPd34iqR4YUw.jpg',
            'chip' => '987654321',
            'tipo' => 'Cria',
            'id_raza' => 2,
            'user_id' => 2,
        ]);

        DB::table('perros')->insert([
            'nombre' => 'Moka',
            'distintivo' => 'Mokita',
            'sexo' => 'Hembra',
            'peso' => 15.2,
            'tamano' => 'Mediano',
            'estatus' => 1,
            'esterilizado' => 'Si',
            'fecha_nacimiento' => '2020-05-01',
            'imagen' => 'https://proyecto9o.s3.amazonaws.com/images/LF3fYwHcA3CKnjMn5jjmFsjUb9eqbPd34iqR4YUw.jpg',
            'chip' => '987654321',
            'tipo' => 'Cria',
            'id_raza' => 2,
            'user_id' => 2,
        ]);
    }
}
