<?php

namespace Database\Seeders;

use App\Models\Gestora;
use Illuminate\Database\Seeder;

class GestoraSeeder extends Seeder
{
    public function run(): void
    {
        Gestora::create([
            'nombre' => 'Fincas Mediterraneo',
            'comision_porcentaje' => 10,
        ]);

        Gestora::create([
            'nombre' => 'Administraciones Norte',
            'comision_porcentaje' => 5,
        ]);
    }
}
