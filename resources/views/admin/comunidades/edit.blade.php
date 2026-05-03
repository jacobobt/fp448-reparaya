@extends('layouts.app')

@section('title', 'Editar comunidad - ReparaYa')

@section('content')
    <h1>Editar comunidad</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.comunidades.index') }}">Volver</a>
    </div>

    @if ($errors->any())
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.comunidades.update', $comunidad) }}">
        @csrf
        @method('PATCH')

        <label>Gestora</label><br>
        <select name="gestora_id" required>
            <option value="">Selecciona una gestora</option>
            @foreach ($gestoras as $gestora)
                <option value="{{ $gestora->id }}" @selected(old('gestora_id', $comunidad->gestora_id) == $gestora->id)>
                    {{ $gestora->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <label>Zona</label><br>
        <select name="zona_id" required>
            <option value="">Selecciona una zona</option>
            @foreach ($zonas as $zona)
                <option value="{{ $zona->id }}" @selected(old('zona_id', $comunidad->zona_id) == $zona->id)>
                    {{ $zona->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <label>Nombre</label><br>
        <input type="text" name="nombre" value="{{ old('nombre', $comunidad->nombre) }}" required><br><br>

        <label>Dirección</label><br>
        <input type="text" name="direccion" value="{{ old('direccion', $comunidad->direccion) }}" required><br><br>

        <button type="submit">Actualizar</button>
    </form>
@endsection
