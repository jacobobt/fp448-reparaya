@extends('layouts.app')

@section('title', 'Gestoras - ReparaYa')

@section('content')
    <h1>Gestoras</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.dashboard') }}">Panel administrador</a>
        <a class="button" href="{{ route('admin.gestoras.create') }}">Nueva gestora</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Comisión</th>
            <th>Usuario de acceso</th>
            <th>Comunidades</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($gestoras as $gestora)
            <tr>
                <td>{{ $gestora->nombre }}</td>
                <td>{{ number_format($gestora->comision_porcentaje, 2) }} %</td>
                <td>
                    @if ($gestora->usuarios->first())
                        {{ $gestora->usuarios->first()->email }}
                    @else
                        <span style="color:#dc2626">Sin usuario</span>
                    @endif
                </td>
                <td>{{ $gestora->comunidades_count }}</td>
                <td>
                    <a href="{{ route('admin.gestoras.edit', $gestora) }}">Editar</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay gestoras registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
