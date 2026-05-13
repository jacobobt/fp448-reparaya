<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Models\Incidencia;
use App\Models\Zona;
use App\Services\IncidenciaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidenciaController extends Controller
{
    public function __construct(private IncidenciaService $servicio)
    {
    }

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
        $zonas          = Zona::orderBy('nombre')->get();

        return view('incidencias.create', compact('especialidades', 'zonas'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'telefono_contacto' => ['required', 'string', 'max:20'],
            'especialidad_id'   => ['required', 'exists:especialidades,id'],
            'zona_id'           => ['required', 'exists:zonas,id'],
            'descripcion'       => ['required', 'string'],
            'direccion'         => ['required', 'string', 'max:255'],
            'fecha_servicio'    => ['required', 'date'],
            'franja_horaria'    => ['required', 'in:09:00-13:00,16:00-20:00'],
            'tipo_urgencia'     => ['required', 'in:Estandar,Urgente'],
        ]);

        $fechaServicio = $datos['fecha_servicio'] . ' ' . substr($datos['franja_horaria'], 0, 5) . ':00';
        $especialidad = Especialidad::findOrFail($datos['especialidad_id']);
        $precioFinal = $especialidad->precio;


        Incidencia::create([
            'localizador'       => $this->servicio->generarLocalizador(),
            'telefono_contacto' => $datos['telefono_contacto'],
            'franja_horaria'    => $datos['franja_horaria'],
            'cliente_id'        => Auth::id(),
            'especialidad_id'   => $datos['especialidad_id'],
            'zona_id'           => $datos['zona_id'],
            'descripcion'       => $datos['descripcion'],
            'direccion'         => $datos['direccion'],
            'fecha_servicio'    => $fechaServicio,
            'tipo_urgencia'     => $datos['tipo_urgencia'],
            'estado'            => 'Pendiente',
            'precio_final' => $precioFinal,
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

        $incidencia->update(['estado' => 'Cancelada']);

        return back()->with('success', 'Incidencia cancelada correctamente.');
    }
}