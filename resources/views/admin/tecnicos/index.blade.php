@extends('layouts.app')

@section('title', 'Técnicos - ReparaYa')

@push('styles')
<style>
    .filtros { background:white; padding:16px 20px; border-radius:8px; border:1px solid #d9e2ec; margin-bottom:16px; display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end; }
    .filtros label { display:block; font-size:12px; font-weight:bold; color:#555; margin-bottom:4px; }
    .filtros input, .filtros select { max-width:200px; padding:7px 10px; font-size:13px; }
    .filtros button { padding:7px 16px; font-size:13px; }
    .btn-reset { background:#e5e7eb; color:#374151; border:none; padding:7px 14px; border-radius:6px; font-weight:bold; cursor:pointer; font-size:13px; }
    .badge-si { background:#dcfce7; color:#166534; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:bold; }
    .badge-no { background:#f3f4f6; color:#6b7280; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:bold; }
    .pagination { display:flex; gap:6px; margin-top:16px; justify-content:center; flex-wrap:wrap; }
    .pagination a, .pagination span { padding:6px 12px; border-radius:6px; border:1px solid #d9e2ec; background:white; color:#0f766e; text-decoration:none; font-size:13px; }
    .pagination span.current { background:#0f766e; color:white; border-color:#0f766e; }
    .pagination span.dots { border:none; background:none; color:#6b7280; }
    .total-info { font-size:13px; color:#6b7280; margin-top:8px; }
</style>
@endpush

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:16px;">
    <h1 style="margin:0;">Técnicos</h1>
    <div style="display:flex; gap:8px;">
        <a class="button" href="{{ route('admin.tecnicos.create') }}">+ Nuevo técnico</a>
        <a class="button" href="{{ route('admin.dashboard') }}" style="background:#e5e7eb; color:#374151;">← Panel</a>
    </div>
</div>

@if (session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

{{-- FILTROS --}}
<form method="GET" action="{{ route('admin.tecnicos.index') }}">
    <div class="filtros">
        <div>
            <label>Buscar</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nombre o email…">
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
        <div>
            <label>Disponibilidad</label>
            <select name="disponible">
                <option value="">Todos</option>
                <option value="1" @selected(request('disponible') === '1')>Disponible</option>
                <option value="0" @selected(request('disponible') === '0')>No disponible</option>
            </select>
        </div>
        <div style="display:flex; gap:8px; align-items:flex-end;">
            <button type="submit">🔍 Filtrar</button>
            <a href="{{ route('admin.tecnicos.index') }}" class="btn-reset">✕ Limpiar</a>
        </div>
    </div>
</form>

<p class="total-info">Mostrando {{ $tecnicos->firstItem() ?? 0 }}–{{ $tecnicos->lastItem() ?? 0 }} de {{ $tecnicos->total() }} técnicos</p>

<table>
    <thead>
    <tr>
        <th>Nombre completo</th>
        <th>Usuario asociado</th>
        <th>Especialidad</th>
        <th>Disponible</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($tecnicos as $tecnico)
        <tr>
            <td><strong>{{ $tecnico->nombre_completo }}</strong></td>
            <td>{{ $tecnico->usuario->email ?? '<span style="color:#9ca3af">Sin usuario</span>' }}</td>
            <td>{{ $tecnico->especialidad->nombre_especialidad ?? '—' }}</td>
            <td>
                @if($tecnico->disponible)
                    <span class="badge-si">Sí</span>
                @else
                    <span class="badge-no">No</span>
                @endif
            </td>
            <td style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('admin.tecnicos.edit', $tecnico) }}" class="button" style="font-size:12px; padding:5px 10px;">Editar</a>
                <form method="POST" action="{{ route('admin.tecnicos.disponibilidad', $tecnico) }}" style="display:inline">
                    @csrf @method('PATCH')
                    <button type="submit" style="font-size:12px; padding:5px 10px; background:{{ $tecnico->disponible ? '#dc2626' : '#16a34a' }}">
                        {{ $tecnico->disponible ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" style="text-align:center; padding:24px; color:#6b7280;">No hay técnicos con los filtros aplicados.</td></tr>
    @endforelse
    </tbody>
</table>

@if ($tecnicos->hasPages())
    <div class="pagination">
        @if ($tecnicos->onFirstPage())
            <span>‹</span>
        @else
            <a href="{{ $tecnicos->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}">‹</a>
        @endif

        @foreach ($tecnicos->getUrlRange(1, $tecnicos->lastPage()) as $page => $url)
            @if ($page == $tecnicos->currentPage())
                <span class="current">{{ $page }}</span>
            @elseif (abs($page - $tecnicos->currentPage()) <= 2 || $page == 1 || $page == $tecnicos->lastPage())
                <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}">{{ $page }}</a>
            @elseif (abs($page - $tecnicos->currentPage()) == 3)
                <span class="dots">…</span>
            @endif
        @endforeach

        @if ($tecnicos->hasMorePages())
            <a href="{{ $tecnicos->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}">›</a>
        @else
            <span>›</span>
        @endif
    </div>
@endif

@endsection