<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'dueño', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'voluntario', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}