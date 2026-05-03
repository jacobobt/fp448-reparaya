@extends('layouts.app')

@section('title', 'Liquidaciones - ReparaYa')

@section('content')
    <h1>Liquidaciones de gestoras</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.dashboard') }}">Panel administrador</a>
    </div>

    <p>
        Se muestran los servicios finalizados tramitados por gestoras y el importe pendiente de liquidar por mes.
    </p>

    <table>
        <thead>
        <tr>
            <th>Gestora</th>
            <th>Mes</th>
            <th>Servicios finalizados</th>
            <th>Total facturado</th>
            <th>Total comisión</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($liquidaciones as $liquidacion)
            <tr>
                <td>{{ $liquidacion->gestora->nombre ?? '-' }}</td>
                <td>{{ $liquidacion->mes }}</td>
                <td>{{ $liquidacion->total_servicios }}</td>
                <td>{{ number_format($liquidacion->total_facturado, 2) }} €</td>
                <td>{{ number_format($liquidacion->total_comision, 2) }} €</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay servicios finalizados tramitados por gestoras.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
