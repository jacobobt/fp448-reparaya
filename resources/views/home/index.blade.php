@extends('layouts.app')

@section('title', 'Inicio - ReparaYa')

@section('content')
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
            <div class="number">{{ $totalIncidencias }}</div>
            <div>Incidencias registradas</div>
        </div>
    </section>

    <h2>Últimas incidencias</h2>

    <table>
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Cliente</th>
            <th>Especialidad</th>
            <th>Técnico</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($ultimasIncidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->cliente->nombre ?? 'Sin cliente' }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>{{ $incidencia->estado }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay incidencias registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
