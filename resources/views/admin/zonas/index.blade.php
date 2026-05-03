@extends('layouts.app')

@section('title', 'Zonas - ReparaYa')

@section('content')
    <h1>Zonas</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.dashboard') }}">Panel administrador</a>
        <a class="button" href="{{ route('admin.zonas.create') }}">Nueva zona</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($zonas as $zona)
            <tr>
                <td>{{ $zona->nombre }}</td>
                <td>
                    <a href="{{ route('admin.zonas.edit', $zona) }}">Editar</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2">No hay zonas registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
