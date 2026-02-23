<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador Alertics',
            'email' => 'admin@alertics.com',
            'password' => Hash::make('admin1234'), 
            'role' => 'admin',
        ]);

        // 2. Creamos un usuario de prueba normal
        User::create([
            'name' => 'Usuario de Prueba',
            'email' => 'user@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);

        // 3. Llamamos al Seeder de tipos de emergencia
        $this->call(EmergencyTypeSeeder::class);
    }
}