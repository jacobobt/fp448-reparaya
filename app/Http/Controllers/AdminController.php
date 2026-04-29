<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Tecnico;
use App\Models\Usuario;
use App\Models\Especialidad;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $this->autorizarAdmin();

        return view('admin.dashboard', [
            'totalUsuarios' => Usuario::count(),
            'totalTecnicos' => Tecnico::count(),
            'pendientes' => Incidencia::where('estado', 'Pendiente')->count(),
            'asignadas' => Incidencia::where('estado', 'Asignada')->count(),
            'finalizadas' => Incidencia::where('estado', 'Finalizada')->count(),
            'canceladas' => Incidencia::where('estado', 'Cancelada')->count(),
            'ultimasIncidencias' => Incidencia::with(['cliente', 'especialidad', 'tecnico'])
                ->latest('created_at')
                ->take(10)
                ->get(),
        ]);
    }

    public function incidencias()
    {
        $this->autorizarAdmin();

        $incidencias = Incidencia::with(['cliente', 'especialidad', 'tecnico'])
          ->latest('created_at')
          ->get();

        return view('admin.incidencias.index', compact('incidencias'));
    }

    public function cambiarEstado(Request $request, Incidencia $incidencia)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'estado' => ['required', 'in:Pendiente,Asignada,Finalizada,Cancelada'],
        ]);

        $incidencia->update([
            'estado' => $datos['estado'],
        ]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    private function autorizarAdmin(): void
    {
        if (!auth()->check() || auth()->user()->rol !== 'admin') {
            abort(403);
        }
    }
}
