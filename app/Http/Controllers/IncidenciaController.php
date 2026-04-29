<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenciaController extends Controller
{
    public function index()
    {
        $incidencias = Incidencia::with(['especialidad', 'tecnico'])
            ->where('cliente_id', Auth::id())
            ->latest('created_at')
            ->get();

        return view('incidencias.index', compact('incidencias'));
    }

    public function create()
    {
        $especialidades = Especialidad::orderBy('nombre_especialidad')->get();

        return view('incidencias.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'telefono_contacto' => ['required', 'string', 'max:20'],
            'especialidad_id' => ['required', 'exists:especialidades,id'],
            'descripcion' => ['required', 'string'],
            'direccion' => ['required', 'string', 'max:255'],
            'fecha_servicio' => ['required', 'date'],
            'franja_horaria' => ['required', 'in:09:00-13:00,16:00-20:00'],
            'tipo_urgencia' => ['required', 'in:Estandar,Urgente'],
        ]);

        $fechaServicio = $datos['fecha_servicio'] . ' ' . substr($datos['franja_horaria'], 0, 5) . ':00';

        Incidencia::create([
            'localizador' => $this->generarLocalizador(),
            'telefono_contacto' => $datos['telefono_contacto'],
            'franja_horaria' => $datos['franja_horaria'],
            'cliente_id' => Auth::id(),
            'especialidad_id' => $datos['especialidad_id'],
            'descripcion' => $datos['descripcion'],
            'direccion' => $datos['direccion'],
            'fecha_servicio' => $fechaServicio,
            'tipo_urgencia' => $datos['tipo_urgencia'],
            'estado' => 'Pendiente',
        ]);

        return redirect()
            ->route('incidencias.index')
            ->with('success', 'Aviso creado correctamente.');
    }

    public function cancelar(Incidencia $incidencia)
    {
        if ($incidencia->cliente_id !== Auth::id()) {
            abort(403);
        }

        if ($incidencia->estado === 'Cancelada') {
            return back()->with('error', 'La incidencia ya está cancelada.');
        }

        if (now()->diffInHours($incidencia->fecha_servicio, false) < 48) {
            return back()->with('error', 'No se puede cancelar una incidencia con menos de 48 horas de antelación.');
        }

        $incidencia->update([
            'estado' => 'Cancelada',
        ]);

        return back()->with('success', 'Incidencia cancelada correctamente.');
    }

    private function generarLocalizador(): string
    {
        $anio = now()->format('Y');
        $numero = Incidencia::whereYear('created_at', $anio)->count() + 1;

        return 'REP-' . $anio . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
