<?php

namespace Database\Seeders;

use App\Models\Tecnico;
use Illuminate\Database\Seeder;

class TecnicoSeeder extends Seeder
{
    public function run(): void
    {
        Tecnico::create([
            'usuario_id' => 2,
            'nombre_completo' => 'Nacho Garcia Fontanero',
            'especialidad_id' => 1,
            'disponible' => true,
        ]);

        Tecnico::create([
            'usuario_id' => 3,
            'nombre_completo' => 'Marcos Gutierrez Cerrajero',
            'especialidad_id' => 2,
            'disponible' => true,
        ]);

        Tecnico::create([
            'usuario_id' => 4,
            'nombre_completo' => 'Paco Ruiz Electricista',
            'especialidad_id' => 3,
            'disponible' => false,
        ]);
    }
}
