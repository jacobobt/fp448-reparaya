<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Support\Facades\DB;

class ZonasController extends Controller
{
    public function incidenciasPorZona()
    {
        // Total de incidencias finalizadas
        $totalGlobal = Incidencia::where('estado', 'Finalizada')->count();

        // Si no hay incidencias finalizadas
        if ($totalGlobal == 0) {
            return response()->json([]);
        }

        // Agrupamos incidencias por zona
        $zonas = Incidencia::where('estado', 'Finalizada')
            ->join('zonas', 'incidencias.zona_id', '=', 'zonas.id')
            ->select(
                'zonas.nombre',
                DB::raw('COUNT(*) as total_incidencias')
            )
            ->groupBy('zonas.id', 'zonas.nombre')
            ->get();

        // Añadimos porcentaje
        $resultado = $zonas->map(function ($zona) use ($totalGlobal) {
            return [
                'zona' => $zona->nombre,
                'total_incidencias' => (int) $zona->total_incidencias,
                'porcentaje' => round(($zona->total_incidencias / $totalGlobal) * 100, 2),
            ];
        });

        return response()->json($resultado);
    }
}
