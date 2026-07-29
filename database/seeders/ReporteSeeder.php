<?php

namespace Database\Seeders;

use App\Models\Reporte;
use App\Models\Role;
use App\Models\User;
use App\Models\Zona;
use Illuminate\Database\Seeder;

class ReporteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('es_MX');
        $duenoRole = Role::where('name', 'dueño')->first();
        $duenos = User::where('role_id', $duenoRole->id)->get();
        $zonas = Zona::all();

        $nombresMascotas = [
            'Mezcal', 'Tule', 'Alebrije', 'Frida', 'Catrina', 'Canela',
            'Colibrí', 'Nochebuena', 'Guelaguetza', 'Chispa', 'Pelusa',
            'Zapoteco', 'Jícara', 'Cempasúchil', 'Copal',
        ];
        $especies = ['Perro', 'Gato', 'Ave', 'Otro'];
        $estados = ['Perdido', 'Perdido', 'Encontrado', 'Perdido', 'Encontrado'];

        foreach ($nombresMascotas as $i => $mascota) {
            Reporte::create([
                'user_id' => $duenos->random()->id,
                'zona_id' => $zonas->random()->id,
                'numero_reporte' => 'RP-2026-' . (1000 + $i),
                'mascota' => $mascota,
                'especie' => $especies[$i % count($especies)],
                'estado' => $estados[$i % count($estados)],
                'fecha_perdida' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                'lat' => $zonas->random()->lat + $faker->randomFloat(6, -0.02, 0.02),
                'lng' => $zonas->random()->lng + $faker->randomFloat(6, -0.02, 0.02),
            ]);
        }
    }
}