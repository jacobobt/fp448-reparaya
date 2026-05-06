@extends('layouts.app')

@section('title', 'Incidencias - Admin ReparaYa')

@push('styles')
<style>
    .filtros { background:white; padding:16px 20px; border-radius:8px; border:1px solid #d9e2ec; margin-bottom:16px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; }
    .filtros label { display:block; font-size:12px; font-weight:bold; color:#555; margin-bottom:4px; }
    .filtros input, .filtros select { max-width:180px; padding:7px 10px; font-size:13px; }
    .filtros button { padding:7px 16px; font-size:13px; }
    .btn-reset { background:#e5e7eb; color:#374151; border:none; padding:7px 14px; border-radius:6px; font-weight:bold; cursor:pointer; font-size:13px; }
    .badge-urgente  { background:#fef2f2; color:#dc2626; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:bold; white-space:nowrap; }
    .badge-estandar { background:#e6fffa; color:#0f766e; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:bold; white-space:nowrap; }
    .badge-pendiente  { background:#fef9c3; color:#854d0e; padding:2px 8px; border-radius:20px; font-size:12px; }
    .badge-asignada   { background:#dbeafe; color:#1e40af; padding:2px 8px; border-radius:20px; font-size:12px; }
    .badge-finalizada { background:#dcfce7; color:#166534; padding:2px 8px; border-radius:20px; font-size:12px; }
    .badge-cancelada  { background:#f3f4f6; color:#6b7280; padding:2px 8px; border-radius:20px; font-size:12px; }
    .pagination { display:flex; gap:6px; margin-top:16px; justify-content:center; flex-wrap:wrap; }
    .pagination a, .pagination span { padding:6px 12px; border-radius:6px; border:1px solid #d9e2ec; background:white; color:#0f766e; text-decoration:none; font-size:13px; }
    .pagination span.current { background:#0f766e; color:white; border-color:#0f766e; }
    .pagination span.dots { border:none; background:none; color:#6b7280; }
    .total-info { font-size:13px; color:#6b7280; margin-top:8px; }
</style>
@endpush

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:16px;">
    <h1 style="margin:0;">Gestión de incidencias</h1>
    <a class="button" href="{{ route('admin.dashboard') }}">← Panel administrador</a>
</div>

@if (session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

{{-- FILTROS --}}
<form method="GET" action="{{ route('admin.incidencias.index') }}">
    <div class="filtros">
        <div>
            <label>Buscar</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Localizador, cliente, dirección…">
        </div>
        <div>
            <label>Estado</label>
            <select name="estado">
                <option value="">Todos</option>
                @foreach (['Pendiente','Asignada','Finalizada','Cancelada'] as $e)
                    <option value="{{ $e }}" @selected(request('estado') === $e)>{{ $e }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Urgencia</label>
            <select name="urgencia">
                <option value="">Todas</option>
                <option value="Urgente"  @selected(request('urgencia') === 'Urgente')>Urgente</option>
                <option value="Estandar" @selected(request('urgencia') === 'Estandar')>Estándar</option>
            </select>
        </div>
        <div>
            <label>Especialidad</label>
            <select name="especialidad_id">
                <option value="">Todas</option>
                @foreach ($especialidades as $esp)
                    <option value="{{ $esp->id }}" @selected(request('especialidad_id') == $esp->id)>{{ $esp->nombre_especialidad }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex; gap:8px; align-items:flex-end;">
            <button type="submit">🔍 Filtrar</button>
            <a href="{{ route('admin.incidencias.index') }}" class="btn-reset">✕ Limpiar</a>
        </div>
    </div>
</form>

<p class="total-info">Mostrando {{ $incidencias->firstItem() ?? 0 }}–{{ $incidencias->lastItem() ?? 0 }} de {{ $incidencias->total() }} incidencias</p>

<table>
    <thead>
    <tr>
        <th>Localizador</th>
        <th>Cliente</th>
        <th>Especialidad</th>
        <th>Dirección</th>
        <th>Fecha</th>
        <th>Urgencia</th>
        <th>Asignar técnico</th>
        <th>Estado</th>
        <th>Cambiar estado</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($incidencias as $incidencia)
        <tr>
            <td><strong>{{ $incidencia->localizador }}</strong></td>
            <td>{{ $incidencia->cliente->nombre ?? '-' }}<br><small style="color:#6b7280;">{{ $incidencia->telefono_contacto }}</small></td>
            <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
            <td style="font-size:13px;">{{ $incidencia->direccion }}</td>
            <td style="white-space:nowrap;">{{ $incidencia->fecha_servicio->format('d/m/Y') }}<br><small style="color:#6b7280;">{{ $incidencia->fecha_servicio->format('H:i') }} · {{ $incidencia->franja_horaria }}</small></td>
            <td>
                @if($incidencia->tipo_urgencia === 'Urgente')
                    <span class="badge-urgente">Urgente</span>
                @else
                    <span class="badge-estandar">Estándar</span>
                @endif
            </td>
            <td>
                <div style="font-size:13px; margin-bottom:4px;">{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</div>
                <form method="POST" action="{{ route('admin.incidencias.asignar', $incidencia) }}" style="display:flex; gap:6px; flex-wrap:wrap;">
                    @csrf @method('PATCH')
                    <select name="tecnico_id" required style="max-width:160px; font-size:12px; padding:4px 6px;">
                        <option value="">Seleccionar…</option>
                        @foreach ($tecnicos as $tecnico)
                            <option value="{{ $tecnico->id }}" @selected($incidencia->tecnico_id === $tecnico->id)>
                                {{ $tecnico->nombre_completo }}{{ $tecnico->especialidad ? ' · '.$tecnico->especialidad->nombre_especialidad : '' }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" style="font-size:12px; padding:4px 10px;">Asignar</button>
                </form>
            </td>
            <td>
                @php $estadoClass = strtolower($incidencia->estado); @endphp
                <span class="badge-{{ $estadoClass }}">{{ $incidencia->estado }}</span>
            </td>
            <td>
                <form method="POST" action="{{ route('admin.incidencias.estado', $incidencia) }}" style="display:flex; gap:6px;">
                    @csrf @method('PATCH')
                    <select name="estado" style="font-size:12px; padding:4px 6px; max-width:120px;">
                        @foreach (['Pendiente','Asignada','Finalizada','Cancelada'] as $estado)
                            <option value="{{ $estado }}" @selected($incidencia->estado === $estado)>{{ $estado }}</option>
                        @endforeach
                    </select>
                    <button type="submit" style="font-size:12px; padding:4px 10px;">OK</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="9" style="text-align:center; padding:24px; color:#6b7280;">No hay incidencias con los filtros aplicados.</td></tr>
    @endforelse
    </tbody>
</table>

{{-- PAGINACIÓN --}}
@if ($incidencias->hasPages())
    <div class="pagination">
        {{-- Anterior --}}
        @if ($incidencias->onFirstPage())
            <span>‹</span>
        @else
            <a href="{{ $incidencias->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}">‹</a>
        @endif

        {{-- Páginas --}}
        @foreach ($incidencias->getUrlRange(1, $incidencias->lastPage()) as $page => $url)
            @if ($page == $incidencias->currentPage())
                <span class="current">{{ $page }}</span>
            @elseif (abs($page - $incidencias->currentPage()) <= 2 || $page == 1 || $page == $incidencias->lastPage())
                <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}">{{ $page }}</a>
            @elseif (abs($page - $incidencias->currentPage()) == 3)
                <span class="dots">…</span>
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($incidencias->hasMorePages())
            <a href="{{ $incidencias->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}">›</a>
        @else
            <span>›</span>
        @endif
    </div>
@endif

@endsection