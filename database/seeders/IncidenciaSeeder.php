<?php

namespace Database\Seeders;

use App\Models\Incidencia;
use Illuminate\Database\Seeder;

class IncidenciaSeeder extends Seeder
{
    public function run(): void
    {
        Incidencia::create([
            'localizador' => 'REP-2026-0001',
            'telefono_contacto' => '111111111',
            'franja_horaria' => '16:00-20:00',
            'cliente_id' => 5,
            'tecnico_id' => 1,
            'especialidad_id' => 1,
            'gestora_id' => null,
            'comunidad_id' => null,
            'zona_id' => 1,
            'descripcion' => 'Acaba de reventar una tuberia en mi ducha necesito reparacion urgente',
            'direccion' => 'calle alegria num 4',
            'fecha_servicio' => '2026-04-12 16:00:00',
            'tipo_urgencia' => 'Urgente',
            'estado' => 'Asignada',
            'precio_final' => 25.00,
            'comision_gestora' => null,
        ]);

        Incidencia::create([
            'localizador' => 'REP-2026-0002',
            'telefono_contacto' => '616166116',
            'franja_horaria' => '09:00-13:00',
            'cliente_id' => 6,
            'tecnico_id' => 2,
            'especialidad_id' => 2,
            'gestora_id' => 1,
            'comunidad_id' => 1,
            'zona_id' => 2,
            'descripcion' => 'Quiero cambiar la cerradura de mi garaje y necesito ayuda',
            'direccion' => 'Avenida buenas vibras 12, 5t, 3a',
            'fecha_servicio' => '2026-04-17 09:00:00',
            'tipo_urgencia' => 'Estandar',
            'estado' => 'Asignada',
            'precio_final' => 30.00,
            'comision_gestora' => 3.00,
        ]);
    }
}
