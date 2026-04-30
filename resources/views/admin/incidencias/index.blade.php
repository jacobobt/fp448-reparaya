@extends('layouts.app')

@section('title', 'Incidencias - Admin ReparaYa')

@section('content')
    <h1>Gestión de incidencias</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.dashboard') }}">Panel administrador</a>
    </div>

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
            <th>Urgencia</th>
            <th>Técnico</th>
            <th>Estado</th>
            <th>Cambiar estado</th>
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
                <td>{{ $incidencia->tipo_urgencia }}</td>
                <td>
                    <div>
                        {{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}
                    </div>

                    <form method="POST" action="{{ route('admin.incidencias.asignar', $incidencia) }}">
                        @csrf
                        @method('PATCH')

                        <select name="tecnico_id" required>
                            <option value="">Seleccionar técnico</option>
                            @foreach ($tecnicos as $tecnico)
                                <option value="{{ $tecnico->id }}" @selected($incidencia->tecnico_id === $tecnico->id)>
                                    {{ $tecnico->nombre_completo }}
                                    @if ($tecnico->especialidad)
                                        - {{ $tecnico->especialidad->nombre_especialidad }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <button type="submit">Asignar</button>
                    </form>
                </td>
                <td>{{ $incidencia->estado }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.incidencias.estado', $incidencia) }}">
                        @csrf
                        @method('PATCH')

                        <select name="estado">
                            @foreach (['Pendiente', 'Asignada', 'Finalizada', 'Cancelada'] as $estado)
                                <option value="{{ $estado }}" @selected($incidencia->estado === $estado)>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit">Guardar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No hay incidencias registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
