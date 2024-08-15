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
        'nombre' => 'Martilo',
        'apellido_paterno' => 'Vazquez',
        'usuario' => 'Marti',
        'telefono' => '8718458147',
        'direccion' => 'Calle San Juan, 553',
        'ciudad' => 'Torreon',
        'codigo_postal' => '87244',
        'colonia' => 'centro',
        'estado' => 'Coahuila',
        'activo' => 1,
        'role_id' => 1,
        'email' => 'martilo@gmail.com',
        'password' => bcrypt('dpav@2024'),
     ]);

        DB::table('users')->insert([
            'nombre' => 'Yordi',
            'apellido_paterno' => 'Ortiz',
            'usuario' =>'Yordi',
            'telefono' => '8287526354',
            'direccion' => 'calle',
            'ciudad' => 'torreon',
            'colonia' => 'centro',
            'codigo_postal' => '87244',    
            'estado' => 'Coahuila',
            'activo' => 1,
            'role_id' => 2,
            'email' => 'yordiortiz16@gmail.com',
            'password' => bcrypt('dpav@2024'),
        ]);

        DB::table('users')->insert([
            'nombre' => 'Bryan',
            'apellido_paterno' => 'Canedo',
            'usuario' => 'Canaba',
            'telefono' => '8187526354',
            'direccion' => 'calle',
            'ciudad' => 'torreon',
            'codigo_postal' => '87244',
            'estado' => 'Coahuila',
            'activo' => 1,
            'role_id' => 3,
            'email' => 'bryansoe871@gmail.com',
            'password' => bcrypt('dpav@2024'),
        ]);


        DB::table('users')->insert([
            'nombre' => 'jose',
            'apellido_paterno' => 'gasca',
            'usuario' => 'jose',
            'telefono' => '8718458147',
            'direccion' => 'calle',
            'ciudad' => 'torreon',
            'codigo_postal' => '87244',
            'estado' => 'Coahuila',
            'activo' => 1,
            'role_id' => 3,
            'email' => 'pabloalvaradovazquez10@gmail.com',
            'password' => bcrypt('Juventud12@'),
        ]);



    }
}
