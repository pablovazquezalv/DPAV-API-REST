<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
     DB::table('users')->insert([
        'nombre' => 'John Doe',
        'apellido_paterno' => 'torres',
        'usuario' => 'juanpan',
        'telefono' => '8487526354',
        'direccion' => 'Calle San Juan, 553',
        'ciudad' => 'Torreon',
        'codigo_postal' => '87244',
        'estado_id' => 1,
        'activo' => 1,
        'role_id' => 1,
        'email' => 'martilo@gmail.com',
        'password' => bcrypt('dpav@2024'),
     ]);

        DB::table('users')->insert([
            'nombre' => 'yordi',
            'apellido_paterno' => 'ortiz',
            'usuario' =>'yordi',
            'telefono' => '8287526354',
            'direccion' => 'calle',
            'ciudad' => 'torreon',
            'codigo_postal' => '87244',
            'estado_id' => 1,
            'activo' => 1,
            'role_id' => 2,
            'email' => 'yordi@gmail.com',
            'password' => bcrypt('dpav@2024'),
        ]);

        DB::table('users')->insert([
            'nombre' => 'jose',
            'apellido_paterno' => 'gasca',
            'usuario' => 'jose',
            'telefono' => '8187526354',
            'direccion' => 'calle',
            'ciudad' => 'torreon',
            'codigo_postal' => '87244',
            'estado_id' => 1,
            'activo' => 1,
            'role_id' => 3,
            'email' => 'bryan@gmail.com',
            'password' => bcrypt('dpav@2024'),
        ]);





    }
}
