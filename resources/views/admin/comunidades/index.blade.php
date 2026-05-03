@extends('layouts.app')

@section('title', 'Comunidades - ReparaYa')

@section('content')
    <h1>Comunidades</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.dashboard') }}">Panel administrador</a>
        <a class="button" href="{{ route('admin.comunidades.create') }}">Nueva comunidad</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Gestora</th>
            <th>Zona</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($comunidades as $comunidad)
            <tr>
                <td>{{ $comunidad->nombre }}</td>
                <td>{{ $comunidad->direccion }}</td>
                <td>{{ $comunidad->gestora->nombre ?? '-' }}</td>
                <td>{{ $comunidad->zona->nombre ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.comunidades.edit', $comunidad) }}">Editar</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay comunidades registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
