<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $duenoRole = Role::where('name', 'dueño')->first();
        $voluntarioRole = Role::where('name', 'voluntario')->first();

        // Usuario developer/admin fijo, documentado en el README para revisión
        User::create([
            'name' => 'Admin RastroPet',
            'email' => 'admin@rastropet.com',
            'password' => Hash::make('Admin123!'),
            'role_id' => $adminRole->id,
            'phone' => '9511234567',
        ]);

        $duenos = [
            ['name' => 'Ana Martínez López', 'email' => 'ana.martinez@rastropet.com', 'phone' => '9511111111'],
            ['name' => 'Carlos Jiménez Ruiz', 'email' => 'carlos.jimenez@rastropet.com', 'phone' => '9511111112'],
            ['name' => 'Fernanda Gómez Silva', 'email' => 'fernanda.gomez@rastropet.com', 'phone' => '9511111113'],
            ['name' => 'Jorge Hernández Paz', 'email' => 'jorge.hernandez@rastropet.com', 'phone' => '9511111114'],
            ['name' => 'Karla Sánchez Ortiz', 'email' => 'karla.sanchez@rastropet.com', 'phone' => '9511111115'],
        ];

        foreach ($duenos as $d) {
            User::create([
                ...$d,
                'password' => Hash::make('Dueno123!'),
                'role_id' => $duenoRole->id,
            ]);
        }

        $voluntarios = [
            ['name' => 'Luis Torres Vidal', 'email' => 'luis.torres@rastropet.com', 'phone' => '9512222221'],
            ['name' => 'Mariana Cruz Ibáñez', 'email' => 'mariana.cruz@rastropet.com', 'phone' => '9512222222'],
            ['name' => 'Ricardo Flores Nava', 'email' => 'ricardo.flores@rastropet.com', 'phone' => '9512222223'],
            ['name' => 'Sofía Ramírez Cortés', 'email' => 'sofia.ramirez@rastropet.com', 'phone' => '9512222224'],
            ['name' => 'Diego Ramírez Contreras', 'email' => 'diego.ramirez@rastropet.com', 'phone' => '9512222225'],
        ];

        foreach ($voluntarios as $v) {
            User::create([
                ...$v,
                'password' => Hash::make('Voluntario123!'),
                'role_id' => $voluntarioRole->id,
            ]);
        }
    }
}