@extends('layouts.app')

@section('title', 'Técnicos - ReparaYa')

@section('content')
    <h1>Técnicos</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.dashboard') }}">Panel administrador</a>
        <a class="button" href="{{ route('admin.tecnicos.create') }}">Nuevo técnico</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

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
                <td>{{ $tecnico->nombre_completo }}</td>
                <td>{{ $tecnico->usuario->email ?? 'Sin usuario' }}</td>
                <td>{{ $tecnico->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</td>
                <td>{{ $tecnico->disponible ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.tecnicos.edit', $tecnico) }}">Editar</a>

                    <form method="POST" action="{{ route('admin.tecnicos.disponibilidad', $tecnico) }}" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit">
                            {{ $tecnico->disponible ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay técnicos registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
