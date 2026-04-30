<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Admin',
            'email' => 'admin@reparaya.edu',
            'password' => Hash::make('1234'),
            'rol' => 'admin',
            'telefono' => '111223344',
        ]);

        Usuario::create([
            'nombre' => 'Nacho Fontanero',
            'email' => 'nfontanero@reparaya.edu',
            'password' => Hash::make('1234'),
            'rol' => 'tecnico',
            'telefono' => '999999998',
        ]);

        Usuario::create([
            'nombre' => 'Marcos Cerrajero',
            'email' => 'mcerrajero@reparaya.edu',
            'password' => Hash::make('1234'),
            'rol' => 'tecnico',
            'telefono' => '999999999',
        ]);

        Usuario::create([
            'nombre' => 'Paco Electricista',
            'email' => 'pelectricista@reparaya.edu',
            'password' => Hash::make('1234'),
            'rol' => 'tecnico',
            'telefono' => '999999997',
        ]);

        Usuario::create([
            'nombre' => 'Antonia',
            'email' => 'antonia1965@gmail.com',
            'password' => Hash::make('1234'),
            'rol' => 'particular',
            'telefono' => '111111111',
        ]);

        Usuario::create([
            'nombre' => 'Jacobo Perez',
            'email' => 'jperez@gmail.com',
            'password' => Hash::make('1234'),
            'rol' => 'particular',
            'telefono' => '616166116',
        ]);
    }
}
