@extends('layouts.app')

@section('title', 'Nueva comunidad - ReparaYa')

@section('content')
    <h1>Nueva comunidad</h1>

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

    <form method="POST" action="{{ route('admin.comunidades.store') }}">
        @csrf

        <label>Gestora</label><br>
        <select name="gestora_id" required>
            <option value="">Selecciona una gestora</option>
            @foreach ($gestoras as $gestora)
                <option value="{{ $gestora->id }}" @selected(old('gestora_id') == $gestora->id)>
                    {{ $gestora->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <label>Zona</label><br>
        <select name="zona_id" required>
            <option value="">Selecciona una zona</option>
            @foreach ($zonas as $zona)
                <option value="{{ $zona->id }}" @selected(old('zona_id') == $zona->id)>
                    {{ $zona->nombre }}
                </option>
            @endforeach
        </select><br><br>

        <label>Nombre</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <label>Dirección</label><br>
        <input type="text" name="direccion" value="{{ old('direccion') }}" required><br><br>

        <button type="submit">Guardar</button>
    </form>
@endsection
