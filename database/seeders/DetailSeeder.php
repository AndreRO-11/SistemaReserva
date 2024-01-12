<?php

namespace Database\Seeders;

use App\Models\Detail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $details = [
        'Aire acondicionado',
        'Acceso discapacitados',
        'Proyector',
        'Telón',
        'Pantalla interactiva',
        'Pizarra',
        'Amplificación',
        'Computador'
    ];

    public function run(): void
    {
        foreach (self::$details as $detail) {
            Detail::insert([
                'detail' => $detail
            ]);
        }
    }
}
