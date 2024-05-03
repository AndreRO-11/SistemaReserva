<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        Campus::insert([
            'campus' => 'FERNANDO MAY',
            'address' => 'Av. Andrés Bello 720',
            'city'=> 'CHILLAN'
        ]);
        Campus::insert([
            'campus' => 'LA CASTILLA',
            'address' => 'Av. Brasil 1180',
            'city' => 'CHILLAN'
        ]);
        Campus::insert([
            'campus' => 'CONCEPCION',
            'address' => 'Avda. Collao 1202 Casilla 5-C',
            'city' => 'CONCEPCION'
        ]);
    }
}
