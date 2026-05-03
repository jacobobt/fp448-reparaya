@extends('layouts.app')

@section('title', 'Nueva gestora - ReparaYa')

@section('content')
    <h1>Nueva gestora</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.gestoras.index') }}">Volver</a>
    </div>

    @if ($errors->any())
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.gestoras.store') }}">
        @csrf

        <label>Nombre</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <label>Comisión (%)</label><br>
        <input type="number" step="0.01" min="0" max="100" name="comision_porcentaje" value="{{ old('comision_porcentaje', 5) }}" required><br><br>

        <button type="submit">Guardar</button>
    </form>
@endsection
