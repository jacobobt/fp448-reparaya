<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Support\Facades\Auth;
use App\Models\Especialidad;
use Illuminate\Http\Request;

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

    public function crearAviso()
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

        $especialidades = Especialidad::orderBy('nombre_especialidad')->get();

        return view('gestora.avisos.create', compact('gestora', 'comunidades', 'especialidades'));
    }

    public function guardarAviso(Request $request)
    {
        if (!Auth::check() || Auth::user()->rol !== 'gestora') {
            abort(403);
        }

        $gestora = Auth::user()->gestora;

        if (!$gestora) {
            abort(403, 'Este usuario no tiene gestora asociada.');
        }

        $datos = $request->validate([
            'comunidad_id' => ['required', 'exists:comunidades,id'],
            'telefono_contacto' => ['required', 'string', 'max:20'],
            'especialidad_id' => ['required', 'exists:especialidades,id'],
            'descripcion' => ['required', 'string'],
            'fecha_servicio' => ['required', 'date'],
            'franja_horaria' => ['required', 'in:09:00-13:00,16:00-20:00'],
            'tipo_urgencia' => ['required', 'in:Estandar,Urgente'],
        ]);

        $comunidad = $gestora->comunidades()
            ->where('id', $datos['comunidad_id'])
            ->firstOrFail();

        $especialidad = Especialidad::findOrFail($datos['especialidad_id']);
        $precioFinal = $especialidad->precio;
        $comisionGestora = $precioFinal * ($gestora->comision_porcentaje / 100);
        $fechaServicio = $datos['fecha_servicio'] . ' ' . substr($datos['franja_horaria'], 0, 5) . ':00';

        Incidencia::create([
            'localizador' => $this->generarLocalizador(),
            'telefono_contacto' => $datos['telefono_contacto'],
            'franja_horaria' => $datos['franja_horaria'],
            'cliente_id' => Auth::id(),
            'gestora_id' => $gestora->id,
            'comunidad_id' => $comunidad->id,
            'zona_id' => $comunidad->zona_id,
            'especialidad_id' => $especialidad->id,
            'descripcion' => $datos['descripcion'],
            'direccion' => $comunidad->direccion,
            'fecha_servicio' => $fechaServicio,
            'tipo_urgencia' => $datos['tipo_urgencia'],
            'estado' => 'Pendiente',
            'precio_final' => $precioFinal,
            'comision_gestora' => $comisionGestora,
        ]);

        return redirect()
            ->route('gestora.dashboard')
            ->with('success', 'Aviso creado correctamente.');
    }

    private function generarLocalizador(): string
    {
        $anio = now()->format('Y');
        $numero = Incidencia::whereYear('created_at', $anio)->count() + 1;

        return 'REP-' . $anio . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}
