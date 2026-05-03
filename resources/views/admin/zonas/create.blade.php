@extends('layouts.app')

@section('title', 'Nueva zona - ReparaYa')

@section('content')
    <h1>Nueva zona</h1>

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

    <form method="POST" action="{{ route('admin.zonas.store') }}">
        @csrf

        <label>Nombre</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <button type="submit">Guardar</button>
    </form>
@endsection
