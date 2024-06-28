<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RazaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('razas')->insert([
            'nombre' => 'Pastor Aleman',
            'estado' => 1,
        ]);

        DB::table('razas')->insert([
            'nombre' => 'Pitbull',
            'estado' => 1,
        ]);

        DB::table('razas')->insert([
            'nombre' => 'Chihuahua',
            'estado' => 1,
        ]);

        DB::table('razas')->insert([
            'nombre' => 'Labrador',
            'estado' => 1,
        ]);

        DB::table('razas')->insert([
            'nombre' => 'Bulldog',
            'estado' => 0,
        ]);


        
    }
}
