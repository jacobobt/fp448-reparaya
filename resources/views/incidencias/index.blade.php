@extends('layouts.app')

@section('title', 'Mis avisos - ReparaYa')

@section('content')
    <h1>Mis avisos</h1>

    <div class="actions">
        <a class="button" href="{{ route('incidencias.create') }}">Nuevo aviso</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <table>
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Especialidad</th>
            <th>Dirección</th>
            <th>Fecha</th>
            <th>Franja</th>
            <th>Urgencia</th>
            <th>Estado</th>
            <th>Técnico</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $incidencia->direccion }}</td>
                <td>{{ $incidencia->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ $incidencia->franja_horaria }}</td>
                <td>{{ $incidencia->tipo_urgencia }}</td>
                <td>{{ $incidencia->estado }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>
                    @if ($incidencia->estado !== 'Cancelada')
                        <form method="POST" action="{{ route('incidencias.cancelar', $incidencia) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Cancelar</button>
                        </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">Todavía no tienes avisos registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
