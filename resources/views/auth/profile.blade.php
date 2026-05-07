@extends('layouts.app')

@section('title', 'Mi perfil - ReparaYa')

@section('content')
    <h1>Mi perfil</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <label>Nombre</label><br>
        <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required><br><br>

        <label>Telefono</label><br>
        <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"><br><br>

        <label>Nueva contrasena</label><br>
        <input type="password" name="password"><br><br>

        <p style="font-size: 13px; color: #555;">Deja la contrasena vacia si no quieres cambiarla.</p>

        <button type="submit">Guardar cambios</button>
    </form>
@endsection
