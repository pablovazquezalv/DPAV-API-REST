<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nombre' => 'John',
            'apellido_paterno' => 'Doe',
            'telefono' => '1234567890',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
            'role_id' => 3,

            'codigo' => rand(100000, 999999),
        ]);

        User::create([
            'nombre' => 'Jane',
            'apellido_paterno' => 'Smith',
            'telefono' => '0987654321',
            'email' => 'janesmith@example.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
            'role_id' => 3,
            'codigo' => rand(100000, 999999),
        ]);

        User::create([
            'nombre' => 'Admin',
            'apellido_paterno' => 'admin',
            'telefono' => '1234567890',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'activo' => 1,
            'role_id' => 1,
            'codigo' => rand(100000, 999999),
        ]);

    }
}
