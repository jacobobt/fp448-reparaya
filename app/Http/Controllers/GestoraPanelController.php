<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Support\Facades\Auth;

class GestoraPanelController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check() || Auth::user()->rol !== 'gestora') {
            abort(403);
        }

        $gestora = Auth::user()->gestora;

        if (!$gestora) {
            abort(403, 'Este usuario no tiene gestora asociada.');
        }

        $comunidades = $gestora->comunidades()
            ->with('zona')
            ->orderBy('nombre')
            ->get();

        $incidencias = Incidencia::with(['comunidad', 'zona', 'especialidad', 'tecnico'])
            ->where('gestora_id', $gestora->id)
            ->latest('created_at')
            ->get();

        return view('gestora.dashboard', compact('gestora', 'comunidades', 'incidencias'));
    }
}
