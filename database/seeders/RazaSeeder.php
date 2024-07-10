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
            'imagen' => 'https://proyecto9o.s3.amazonaws.com/images/r7oKuKm2CSg4Bnpj8YksJcXZcu2zIteGFMaHTsue.jpg',
            'estado' => 1,
        ]);

       

        DB::table('razas')->insert([
            'nombre' => 'Chihuahua',
            'imagen' => 'https://proyecto9o.s3.amazonaws.com/images/pCkJi2PiY95QfVGMra9QcLhS9GcBeI216VatBjT6.jpg',
            'estado' => 1,
        ]);

       


        
    }
}
