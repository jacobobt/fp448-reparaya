<?php

namespace Database\Seeders;

use App\Models\Zona;
use Illuminate\Database\Seeder;

class ZonaSeeder extends Seeder
{
    public function run(): void
    {
        $zonas = [
            'Centro',
            'Norte',
            'Sur',
            'Este',
            'Oeste',
        ];

        foreach ($zonas as $zona) {
            Zona::create([
                'nombre' => $zona,
            ]);
        }
    }
}
