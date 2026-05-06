<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Tecnico;
use App\Models\Usuario;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use App\Models\Gestora;
use App\Models\Zona;
use App\Models\Comunidad;

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

    public function incidencias(Request $request)
{
    $this->autorizarAdmin();
 
    $query = Incidencia::with(['cliente', 'especialidad', 'tecnico'])
        ->latest('created_at');
 
    // Búsqueda por texto libre
    if ($q = $request->input('q')) {
        $query->where(function ($sub) use ($q) {
            $sub->where('localizador', 'like', "%{$q}%")
                ->orWhere('direccion', 'like', "%{$q}%")
                ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$q}%"));
        });
    }
 
    // Filtro estado
    if ($estado = $request->input('estado')) {
        $query->where('estado', $estado);
    }
 
    // Filtro urgencia
    if ($urgencia = $request->input('urgencia')) {
        $query->where('tipo_urgencia', $urgencia);
    }
 
    // Filtro especialidad
    if ($espId = $request->input('especialidad_id')) {
        $query->where('especialidad_id', $espId);
    }
 
    $incidencias    = $query->paginate(15)->withQueryString();
    $tecnicos       = Tecnico::with('especialidad')->where('disponible', true)->orderBy('nombre_completo')->get();
    $especialidades = \App\Models\Especialidad::orderBy('nombre_especialidad')->get();
 
    return view('admin.incidencias.index', compact('incidencias', 'tecnicos', 'especialidades'));
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

    public function asignarTecnico(Request $request, Incidencia $incidencia)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'tecnico_id' => ['required', 'exists:tecnicos,id'],
        ]);

        $incidencia->update([
            'tecnico_id' => $datos['tecnico_id'],
            'estado' => $incidencia->estado === 'Pendiente' ? 'Asignada' : $incidencia->estado,
        ]);

        return back()->with('success', 'Técnico asignado correctamente.');
    }

    public function especialidades()
        {
            $this->autorizarAdmin();

            $especialidades = Especialidad::orderBy('nombre_especialidad')->get();

            return view('admin.especialidades.index', compact('especialidades'));
    }

    public function crearEspecialidad()
    {
        $this->autorizarAdmin();

        return view('admin.especialidades.create');
    }

    public function guardarEspecialidad(Request $request)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'nombre_especialidad' => ['required', 'string', 'max:50'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        Especialidad::create($datos);

        return redirect()
         ->route('admin.especialidades.index')
         ->with('success', 'Especialidad creada correctamente.');
    }

    public function editarEspecialidad(Especialidad $especialidad)
    {
        $this->autorizarAdmin();

        return view('admin.especialidades.edit', compact('especialidad'));
    }

    public function actualizarEspecialidad(Request $request, Especialidad $especialidad)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'nombre_especialidad' => ['required', 'string', 'max:50'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        $especialidad->update($datos);

        return redirect()
            ->route('admin.especialidades.index')
            ->with('success', 'Especialidad actualizada correctamente.');
    }

    public function tecnicos(Request $request)
{
    $this->autorizarAdmin();
 
    $query = Tecnico::with(['usuario', 'especialidad'])
        ->orderBy('nombre_completo');
 
    // Búsqueda por texto libre
    if ($q = $request->input('q')) {
        $query->where(function ($sub) use ($q) {
            $sub->where('nombre_completo', 'like', "%{$q}%")
                ->orWhereHas('usuario', fn($u) => $u->where('email', 'like', "%{$q}%"));
        });
    }
 
    // Filtro especialidad
    if ($espId = $request->input('especialidad_id')) {
        $query->where('especialidad_id', $espId);
    }
 
    // Filtro disponibilidad
    if ($request->input('disponible') !== null && $request->input('disponible') !== '') {
        $query->where('disponible', (bool) $request->input('disponible'));
    }
 
    $tecnicos       = $query->paginate(20)->withQueryString();
    $especialidades = \App\Models\Especialidad::orderBy('nombre_especialidad')->get();
 
    return view('admin.tecnicos.index', compact('tecnicos', 'especialidades'));
}

    public function crearTecnico()
    {
        $this->autorizarAdmin();

        $usuarios = Usuario::where('rol', 'tecnico')
            ->whereDoesntHave('tecnico')
            ->orderBy('nombre')
            ->get();

        $especialidades = Especialidad::orderBy('nombre_especialidad')->get();

        return view('admin.tecnicos.create', compact('usuarios', 'especialidades'));
    }

    public function guardarTecnico(Request $request)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'usuario_id' => ['nullable', 'exists:usuarios,id', 'unique:tecnicos,usuario_id'],
            'nombre_completo' => ['required', 'string', 'max:100'],
            'especialidad_id' => ['nullable', 'exists:especialidades,id'],
            'disponible' => ['required', 'boolean'],
        ]);

        Tecnico::create($datos);

        return redirect()
            ->route('admin.tecnicos.index')
            ->with('success', 'Técnico creado correctamente.');
    }

    public function editarTecnico(Tecnico $tecnico)
    {
        $this->autorizarAdmin();

        $usuarios = Usuario::where('rol', 'tecnico')
            ->where(function ($query) use ($tecnico) {
                $query->whereDoesntHave('tecnico')
                    ->orWhere('id', $tecnico->usuario_id);
            })
            ->orderBy('nombre')
            ->get();

        $especialidades = Especialidad::orderBy('nombre_especialidad')->get();

        return view('admin.tecnicos.edit', compact('tecnico', 'usuarios', 'especialidades'));
    }

    public function actualizarTecnico(Request $request, Tecnico $tecnico)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'usuario_id' => ['nullable', 'exists:usuarios,id', 'unique:tecnicos,usuario_id,' . $tecnico->id],
            'nombre_completo' => ['required', 'string', 'max:100'],
            'especialidad_id' => ['nullable', 'exists:especialidades,id'],
            'disponible' => ['required', 'boolean'],
        ]);

        $tecnico->update($datos);

        return redirect()
            ->route('admin.tecnicos.index')
            ->with('success', 'Técnico actualizado correctamente.');
    }

    public function cambiarDisponibilidadTecnico(Tecnico $tecnico)
    {
        $this->autorizarAdmin();

        $tecnico->update([
            'disponible' => !$tecnico->disponible,
        ]);

        return back()->with('success', 'Disponibilidad actualizada correctamente.');
    }

    public function gestoras()
    {
    $this->autorizarAdmin();
 
    $gestoras = \App\Models\Gestora::withCount('comunidades')
        ->with('usuarios')
        ->orderBy('nombre')
        ->get();
 
    return view('admin.gestoras.index', compact('gestoras'));
    }

    public function crearGestora()
    {
    $this->autorizarAdmin();
    return view('admin.gestoras.create');
    }
 
    public function guardarGestora(Request $request)
    {
    $this->autorizarAdmin();
 
    $request->validate([
        'nombre'             => ['required', 'string', 'max:255'],
        'comision_porcentaje'=> ['required', 'numeric', 'min:0', 'max:100'],
        'usuario_nombre'     => ['required', 'string', 'max:255'],
        'usuario_email'      => ['required', 'email', 'unique:usuarios,email'],
        'usuario_password'   => ['required', 'string', 'min:8'],
    ]);
 
    // 1. Crear la gestora
    $gestora = \App\Models\Gestora::create([
        'nombre'              => $request->nombre,
        'comision_porcentaje' => $request->comision_porcentaje,
    ]);
 
    // 2. Crear el usuario con rol gestora y asociarlo
    \App\Models\Usuario::create([
        'nombre'      => $request->usuario_nombre,
        'email'       => $request->usuario_email,
        'password'    => $request->usuario_password, // el cast 'hashed' lo encripta automáticamente
        'rol'         => 'gestora',
        'gestora_id'  => $gestora->id,
    ]);
 
    return redirect()
        ->route('admin.gestoras.index')
        ->with('success', 'Gestora y usuario creados correctamente.');
    }

    public function editarGestora(Gestora $gestora)
    {
        $this->autorizarAdmin();

        return view('admin.gestoras.edit', compact('gestora'));
    }

    public function actualizarGestora(Request $request, Gestora $gestora)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'comision_porcentaje' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $gestora->update($datos);

        return redirect()
            ->route('admin.gestoras.index')
            ->with('success', 'Gestora actualizada correctamente.');
    }

    public function zonas()
    {
        $this->autorizarAdmin();

        $zonas = Zona::orderBy('nombre')->get();

        return view('admin.zonas.index', compact('zonas'));
    }

    public function crearZona()
    {
        $this->autorizarAdmin();

        return view('admin.zonas.create');
    }

    public function guardarZona(Request $request)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        Zona::create($datos);

        return redirect()
            ->route('admin.zonas.index')
            ->with('success', 'Zona creada correctamente.');
    }

    public function editarZona(Zona $zona)
    {
        $this->autorizarAdmin();

        return view('admin.zonas.edit', compact('zona'));
    }

    public function actualizarZona(Request $request, Zona $zona)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        $zona->update($datos);

        return redirect()
            ->route('admin.zonas.index')
            ->with('success', 'Zona actualizada correctamente.');
    }

    public function comunidades()
    {
        $this->autorizarAdmin();

        $comunidades = Comunidad::with(['gestora', 'zona'])
            ->orderBy('nombre')
            ->get();

        return view('admin.comunidades.index', compact('comunidades'));
    }

    public function crearComunidad()
    {
        $this->autorizarAdmin();

        $gestoras = Gestora::orderBy('nombre')->get();
        $zonas = Zona::orderBy('nombre')->get();

        return view('admin.comunidades.create', compact('gestoras', 'zonas'));
    }

    public function guardarComunidad(Request $request)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'gestora_id' => ['required', 'exists:gestoras,id'],
            'zona_id' => ['required', 'exists:zonas,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
        ]);

        Comunidad::create($datos);

        return redirect()
            ->route('admin.comunidades.index')
            ->with('success', 'Comunidad creada correctamente.');
    }

    public function editarComunidad(Comunidad $comunidad)
    {
        $this->autorizarAdmin();

        $gestoras = Gestora::orderBy('nombre')->get();
        $zonas = Zona::orderBy('nombre')->get();

        return view('admin.comunidades.edit', compact('comunidad', 'gestoras', 'zonas'));
    }

    public function actualizarComunidad(Request $request, Comunidad $comunidad)
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'gestora_id' => ['required', 'exists:gestoras,id'],
            'zona_id' => ['required', 'exists:zonas,id'],
            'nombre' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
        ]);

        $comunidad->update($datos);

        return redirect()
            ->route('admin.comunidades.index')
            ->with('success', 'Comunidad actualizada correctamente.');
    }

    public function liquidaciones()
    {
        $this->autorizarAdmin();

        $liquidaciones = Incidencia::with('gestora')
            ->whereNotNull('gestora_id')
            ->where('estado', 'Finalizada')
            ->selectRaw('gestora_id')
            ->selectRaw("DATE_FORMAT(fecha_servicio, '%Y-%m') as mes")
            ->selectRaw('COUNT(*) as total_servicios')
            ->selectRaw('SUM(precio_final) as total_facturado')
            ->selectRaw('SUM(comision_gestora) as total_comision')
            ->groupBy('gestora_id', 'mes')
            ->orderByDesc('mes')
            ->get();

        return view('admin.liquidaciones.index', compact('liquidaciones'));
    }

    public function calendario()
    {
    $this->autorizarAdmin();
 
    $incidencias = Incidencia::with(['cliente', 'especialidad', 'tecnico'])
        ->whereIn('estado', ['Pendiente', 'Asignada', 'Finalizada'])
        ->get();
 
    return view('admin.calendario', compact('incidencias'));
    }

    private function autorizarAdmin(): void
    {
        if (!auth()->check() || auth()->user()->rol !== 'admin') {
            abort(403);
        }
    }
}
