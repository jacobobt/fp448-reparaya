@extends('layouts.app')

@section('title', 'Editar especialidad - ReparaYa')

@section('content')
    <h1>Editar especialidad</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.especialidades.index') }}">Volver</a>
    </div>

    @if ($errors->any())
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.especialidades.update', $especialidad) }}">
        @csrf
        @method('PATCH')

        <label>Nombre</label><br>
        <input type="text" name="nombre_especialidad" value="{{ old('nombre_especialidad', $especialidad->nombre_especialidad) }}" required><br><br>

        <label>Precio base</label><br>
        <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio', $especialidad->precio) }}" required><br><br>

        <button type="submit">Actualizar</button>
    </form>
@endsection
