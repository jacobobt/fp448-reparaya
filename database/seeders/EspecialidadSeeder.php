<?php

namespace Database\Seeders;

use App\Models\Especialidad;
use Illuminate\Database\Seeder;

class EspecialidadSeeder extends Seeder
{
    public function run(): void
    {
        $especialidades = [
            ['nombre_especialidad' => 'Fontanero', 'precio' => 25.00],
            ['nombre_especialidad' => 'Cerrajero', 'precio' => 30.00],
            ['nombre_especialidad' => 'Electricista', 'precio' => 50.00],
            ['nombre_especialidad' => 'Mecanico', 'precio' => 40.00],
            ['nombre_especialidad' => 'Informatico', 'precio' => 15.00],
        ];

        foreach ($especialidades as $especialidad) {
            Especialidad::create($especialidad);
        }
    }
}
