<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use App\Models\Incidencia;

class ZonasController extends Controller
{
    /**
     * GET /api/servicios/zonas
     *
     * Devuelve un JSON con datos agregados de servicios por zona.
     * Incluye todas las zonas, aunque no tengan servicios.
     *
     * Ejemplo de respuesta:
     * {
     *   "total_servicios_global": 42,
     *   "zonas": [
     *     {
     *       "nombre_zona": "Centro",
     *       "total_servicios": 18,
     *       "porcentaje": 42.86
     *     },
     *     ...
     *   ]
     * }
     */
    public function incidenciasPorZona()
    {
        // Total global de servicios finalizados
        $totalGlobal = Incidencia::where('estado', 'Finalizada')->count();

        // Obtenemos todas las zonas con el conteo de sus incidencias finalizadas
        // withCount no filtra por estado, así que usamos una relación con condición
        $zonas = Zona::withCount([
            'incidencias as total_servicios' => function ($query) {
                $query->where('estado', 'Finalizada');
            }
        ])
        ->orderBy('nombre')
        ->get();

        $resultado = $zonas->map(function ($zona) use ($totalGlobal) {
            return [
                'nombre_zona'     => $zona->nombre,
                'total_servicios' => $zona->total_servicios,
                'porcentaje'      => $totalGlobal > 0
                    ? round(($zona->total_servicios / $totalGlobal) * 100, 2)
                    : 0.00,
            ];
        });

        return response()->json([
            'total_servicios_global' => $totalGlobal,
            'zonas'                  => $resultado,
        ], 200, [
            'Content-Type'                => 'application/json',
            'Access-Control-Allow-Origin' => '*',   // CORS: permite llamadas desde cualquier origen
        ]);
    }
}