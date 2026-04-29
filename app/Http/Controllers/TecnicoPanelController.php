<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Support\Facades\Auth;

class TecnicoPanelController extends Controller
{
    public function agenda()
    {
        if (!Auth::check() || Auth::user()->rol !== 'tecnico') {
            abort(403);
        }

        $tecnico = Auth::user()->tecnico;

        if (!$tecnico) {
            abort(403, 'Este usuario no tiene técnico asociado.');
        }

        $incidencias = Incidencia::with(['cliente', 'especialidad'])
            ->where('tecnico_id', $tecnico->id)
            ->whereIn('estado', ['Asignada', 'Finalizada'])
            ->orderBy('fecha_servicio')
            ->get();

        return view('tecnico.agenda', compact('tecnico', 'incidencias'));
    }

    public function finalizar(Incidencia $incidencia)
    {
        if (!Auth::check() || Auth::user()->rol !== 'tecnico') {
            abort(403);
        }

        $tecnico = Auth::user()->tecnico;

        if (!$tecnico || $incidencia->tecnico_id !== $tecnico->id) {
            abort(403);
        }

        $incidencia->update([
            'estado' => 'Finalizada',
        ]);

        return back()->with('success', 'Servicio marcado como finalizado.');
    }
}
