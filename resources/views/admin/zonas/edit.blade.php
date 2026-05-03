@extends('layouts.app')

@section('title', 'Editar zona - ReparaYa')

@section('content')
    <h1>Editar zona</h1>

    <div class="actions">
        <a class="button" href="{{ route('admin.zonas.index') }}">Volver</a>
    </div>

    @if ($errors->any())
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.zonas.update', $zona) }}">
        @csrf
        @method('PATCH')

        <label>Nombre</label><br>
        <input type="text" name="nombre" value="{{ old('nombre', $zona->nombre) }}" required><br><br>

        <button type="submit">Actualizar</button>
    </form>
@endsection
