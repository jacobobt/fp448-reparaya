@extends('layouts.app')

@section('title', 'Mis liquidaciones - ReparaYa')

@section('content')
    <h1>Mis liquidaciones</h1>

    <div class="actions">
        <a class="button" href="{{ route('gestora.dashboard') }}">Panel gestora</a>
    </div>

    <p>
        Gestora: {{ $gestora->nombre }}
    </p>

    <h2>Comisiones acumuladas por mes</h2>

    <table>
        <thead>
        <tr>
            <th>Mes</th>
            <th>Servicios finalizados</th>
            <th>Total facturado</th>
            <th>Total comisión</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($liquidaciones as $liquidacion)
            <tr>
                <td>{{ $liquidacion->mes }}</td>
                <td>{{ $liquidacion->total_servicios }}</td>
                <td>{{ number_format($liquidacion->total_facturado, 2) }} €</td>
                <td>{{ number_format($liquidacion->total_comision, 2) }} €</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Todavía no hay servicios finalizados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <h2>Servicios realizados</h2>

    <table>
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Comunidad</th>
            <th>Zona</th>
            <th>Especialidad</th>
            <th>Técnico</th>
            <th>Fecha</th>
            <th>Precio final</th>
            <th>Comisión</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($servicios as $servicio)
            <tr>
                <td>{{ $servicio->localizador }}</td>
                <td>{{ $servicio->comunidad->nombre ?? '-' }}</td>
                <td>{{ $servicio->zona->nombre ?? '-' }}</td>
                <td>{{ $servicio->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $servicio->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>{{ $servicio->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ number_format($servicio->precio_final, 2) }} €</td>
                <td>{{ number_format($servicio->comision_gestora, 2) }} €</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No hay servicios realizados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
