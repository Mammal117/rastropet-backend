<?php

namespace Database\Seeders;

use App\Models\Zona;
use Illuminate\Database\Seeder;

class ZonaSeeder extends Seeder
{
    public function run(): void
    {
        Zona::insert([
            ['nombre' => 'Centro (Oaxaca de Juárez)', 'lat' => 17.0732, 'lng' => -96.7266, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Santa María El Tule', 'lat' => 17.0508, 'lng' => -96.6317, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'San Pablo Etla', 'lat' => 17.1667, 'lng' => -96.8167, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Tlacolula de Matamoros', 'lat' => 16.9506, 'lng' => -96.4772, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Zaachila', 'lat' => 16.9667, 'lng' => -96.7500, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Santa Lucía del Camino', 'lat' => 17.0667, 'lng' => -96.6833, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}