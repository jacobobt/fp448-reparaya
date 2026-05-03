<?php

namespace Database\Seeders;

use App\Models\Comunidad;
use Illuminate\Database\Seeder;

class ComunidadSeeder extends Seeder
{
    public function run(): void
    {
        Comunidad::create([
            'gestora_id' => 1,
            'zona_id' => 2,
            'nombre' => 'Comunidad Avenida Buenas Vibras',
            'direccion' => 'Avenida buenas vibras 12',
        ]);

        Comunidad::create([
            'gestora_id' => 1,
            'zona_id' => 1,
            'nombre' => 'Comunidad Calle Alegria',
            'direccion' => 'calle alegria num 4',
        ]);

        Comunidad::create([
            'gestora_id' => 2,
            'zona_id' => 3,
            'nombre' => 'Comunidad Plaza Sur',
            'direccion' => 'Plaza Sur 8',
        ]);
    }
}
