<?php

namespace Database\Seeders;

use App\Models\Avistamiento;
use App\Models\Reporte;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AvistamientoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('es_MX');
        $voluntarioRole = Role::where('name', 'voluntario')->first();
        $voluntarios = User::where('role_id', $voluntarioRole->id)->get();
        $reportesPerdidos = Reporte::where('estado', 'Perdido')->get();

        foreach ($reportesPerdidos as $reporte) {
            // 1 o 2 avistamientos por reporte perdido, de voluntarios distintos
            $cantidad = rand(1, 2);
            foreach ($voluntarios->random(min($cantidad, $voluntarios->count())) as $voluntario) {
                Avistamiento::create([
                    'reporte_id' => $reporte->id,
                    'user_id' => $voluntario->id,
                    'comentario' => $faker->randomElement([
                        'La vi cerca del mercado, parecía asustada.',
                        'Alguien la tenía atada en su patio, avisé al dueño.',
                        'Cruzó la calle corriendo hacia el parque.',
                        'La vi comiendo en un puesto de comida.',
                    ]),
                    'lat' => $reporte->lat + $faker->randomFloat(6, -0.01, 0.01),
                    'lng' => $reporte->lng + $faker->randomFloat(6, -0.01, 0.01),
                    'fecha' => $faker->dateTimeBetween($reporte->fecha_perdida, 'now'),
                ]);
            }
        }
    }
}