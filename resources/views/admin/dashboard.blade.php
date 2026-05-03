@extends('layouts.app')

@section('title', 'Panel administrador - ReparaYa')

@section('content')
    <h1>Panel administrador</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.incidencias.index') }}">Gestionar incidencias</a>
        <a class="button" href="{{ route('admin.especialidades.index') }}">Gestionar especialidades</a>
        <a class="button" href="{{ route('admin.tecnicos.index') }}">Gestionar técnicos</a>
        <a class="button" href="{{ route('admin.gestoras.index') }}">Gestionar gestoras</a>
        <a class="button" href="{{ route('admin.zonas.index') }}">Gestionar zonas</a>
    </div>

    <section class="stats">
        <div class="card">
            <div class="number">{{ $totalUsuarios }}</div>
            <div>Usuarios registrados</div>
        </div>

        <div class="card">
            <div class="number">{{ $totalTecnicos }}</div>
            <div>Técnicos registrados</div>
        </div>

        <div class="card">
            <div class="number">{{ $pendientes }}</div>
            <div>Incidencias pendientes</div>
        </div>
    </section>

    <h2>Resumen de estados</h2>

    <ul>
        <li>Asignadas: {{ $asignadas }}</li>
        <li>Finalizadas: {{ $finalizadas }}</li>
        <li>Canceladas: {{ $canceladas }}</li>
    </ul>

    <h2>Últimas incidencias</h2>

    <table>
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Cliente</th>
            <th>Especialidad</th>
            <th>Técnico</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($ultimasIncidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->cliente->nombre ?? '-' }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>{{ $incidencia->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ $incidencia->estado }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No hay incidencias registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
