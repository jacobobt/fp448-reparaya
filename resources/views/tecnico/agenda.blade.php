@extends('layouts.app')

@section('title', 'Mi agenda - ReparaYa')

@section('content')
    <h1>Mi agenda</h1>

    <p>
        Técnico: {{ $tecnico->nombre_completo }}
    </p>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Cliente</th>
            <th>Teléfono</th>
            <th>Especialidad</th>
            <th>Dirección</th>
            <th>Fecha</th>
            <th>Franja</th>
            <th>Urgencia</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->cliente->nombre ?? '-' }}</td>
                <td>{{ $incidencia->telefono_contacto }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $incidencia->direccion }}</td>
                <td>{{ $incidencia->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ $incidencia->franja_horaria }}</td>
                <td>{{ $incidencia->tipo_urgencia }}</td>
                <td>{{ $incidencia->estado }}</td>
                <td>
                    @if ($incidencia->estado !== 'Finalizada')
                        <form method="POST" action="{{ route('tecnico.incidencias.finalizar', $incidencia) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Marcar finalizada</button>
                        </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No tienes servicios asignados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
