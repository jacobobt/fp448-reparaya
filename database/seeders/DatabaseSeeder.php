<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EspecialidadSeeder::class,
            GestoraSeeder::class,
            ZonaSeeder::class,
            UsuarioSeeder::class,
            TecnicoSeeder::class,
            ComunidadSeeder::class,
            IncidenciaSeeder::class,
        ]);
    }
}
