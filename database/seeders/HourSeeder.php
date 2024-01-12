<?php

namespace Database\Seeders;

use App\Models\Hour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $hours = [
        '08:10',
        '08:50',
        '09:40',
        '10:20',
        '11:10',
        '11:50',
        '12:40',
        '13:20',
        '14:10',
        '14:50',
        '15:40',
        '16:20',
        '17:10',
        '17:50',
        '18:40',
        '19:20'
    ];

    public function run(): void
    {
        foreach (self::$hours as $hour) {
            Hour::insert([
                'hour' => $hour
            ]);
        }
    }
}
