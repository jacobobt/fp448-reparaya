@extends('layouts.app')

@section('title', 'Nueva especialidad - ReparaYa')

@section('content')
    <h1>Nueva especialidad</h1>

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

    <form method="POST" action="{{ route('admin.especialidades.store') }}">
        @csrf

        <label>Nombre</label><br>
        <input type="text" name="nombre_especialidad" value="{{ old('nombre_especialidad') }}" required><br><br>

        <label>Precio base</label><br>
        <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio') }}" required><br><br>

        <button type="submit">Guardar</button>
    </form>
@endsection
