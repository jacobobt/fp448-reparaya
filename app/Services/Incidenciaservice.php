<?php

namespace App\Services;

use App\Models\Incidencia;
use Illuminate\Support\Facades\DB;

class IncidenciaService
{
    /**
     * Genera un localizador único del tipo REP-2026-0001.
     *
     * Usa una transacción con bloqueo (lockForUpdate) para evitar
     * que dos peticiones simultáneas generen el mismo número.
     */
    public function generarLocalizador(): string
    {
        return DB::transaction(function () {
            $anio = now()->format('Y');

            // lockForUpdate bloquea las filas leídas hasta que
            // acabe la transacción, evitando la race condition
            $ultimo = Incidencia::whereYear('created_at', $anio)
                ->lockForUpdate()
                ->count();

            $numero = $ultimo + 1;

            return 'REP-' . $anio . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
        });
    }
}