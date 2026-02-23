<?php

namespace Database\Seeders;

use App\Models\EmergencyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmergencyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Robo / Asalto', 'color' => '#ef4444'], // Rojo
            ['name' => 'Accidente de Tránsito', 'color' => '#f97316'], // Naranja
            ['name' => 'Incendio', 'color' => '#dc2626'], // Rojo oscuro
            ['name' => 'Emergencia Médica', 'color' => '#3b82f6'], // Azul
            ['name' => 'Persona Sospechosa', 'color' => '#eab308'], // Amarillo
        ];

        foreach ($types as $type) {
            EmergencyType::create([
                'name' => $type['name'],
                'slug' => Str::slug($type['name']),
                'color' => $type['color'],
            ]);
        }
    }
}