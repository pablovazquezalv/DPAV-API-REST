<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('citas')->insert([
            'fecha' => '2021-07-11',
            'codigo' => uniqid(),
            'hora' => '10:00:00',
            'estado' => 'pendiente',
            'motivo' => 'consulta',
            'user_id' => 1,
        ]);

        DB::table('citas')->insert([
            'fecha' => '2021-06-23',
            'codigo' => uniqid(),
            'hora' => '11:00:00',
            'estado' => 'pendiente',
            'motivo' => 'consulta',
            'user_id' => 1,
        ]);}
}
