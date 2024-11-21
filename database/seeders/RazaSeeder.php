<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Raza;

class RazaSeeder extends Seeder
{
    public function run()
    {
        $razas = ['Labrador', 'Pastor Alemán', 'Golden Retriever', 'Bulldog', 'Chihuahua'];

        foreach ($razas as $raza) {
            Raza::create(['nombre' => $raza]);
        }
    }
}
