@extends('layouts.app')

@section('title', 'Especialidades - ReparaYa')

@section('content')
    <h1>Especialidades</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.dashboard') }}">Panel administrador</a>
        <a class="button" href="{{ route('admin.especialidades.create') }}">Nueva especialidad</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Precio base</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($especialidades as $especialidad)
            <tr>
                <td>{{ $especialidad->nombre_especialidad }}</td>
                <td>{{ number_format($especialidad->precio, 2) }} €</td>
                <td>
                    <a href="{{ route('admin.especialidades.edit', $especialidad) }}">Editar</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No hay especialidades registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
