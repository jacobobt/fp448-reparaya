@extends('layouts.app')

@section('title', 'Panel gestora - ReparaYa')

@section('content')
    <h1>Panel gestora</h1>

    <div class="actions">
        <a class="button" href="{{ route('gestora.avisos.create') }}">Nuevo aviso</a>
        <a class="button" href="{{ route('gestora.liquidaciones.index') }}">Mis liquidaciones</a>
    </div>

    <p>
        Gestora: {{ $gestora->nombre }}
        - Comisión: {{ number_format($gestora->comision_porcentaje, 2) }} %
    </p>

    <h2>Comunidades gestionadas</h2>

    <table>
        <thead>
        <tr>
            <th>Comunidad</th>
            <th>Dirección</th>
            <th>Zona</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($comunidades as $comunidad)
            <tr>
                <td>{{ $comunidad->nombre }}</td>
                <td>{{ $comunidad->direccion }}</td>
                <td>{{ $comunidad->zona->nombre ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No hay comunidades asociadas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <h2>Servicios tramitados por la gestora</h2>

    <table>
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Comunidad</th>
            <th>Zona</th>
            <th>Especialidad</th>
            <th>Técnico</th>
            <th>Estado</th>
            <th>Precio final</th>
            <th>Comisión</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->comunidad->nombre ?? '-' }}</td>
                <td>{{ $incidencia->zona->nombre ?? '-' }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>{{ $incidencia->estado }}</td>
                <td>{{ $incidencia->precio_final ? number_format($incidencia->precio_final, 2) . ' €' : '-' }}</td>
                <td>{{ $incidencia->comision_gestora ? number_format($incidencia->comision_gestora, 2) . ' €' : '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No hay servicios tramitados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
