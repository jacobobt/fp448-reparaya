@extends('layouts.app')

@section('title', 'Registro - ReparaYa')

@section('content')
    <h1>Registro de cliente</h1>

    @if ($errors->any())
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Nombre</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required><br><br>

        <label>Teléfono</label><br>
        <input type="text" name="telefono" value="{{ old('telefono') }}"><br><br>

        <label>Contraseña</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Registrarme</button>
    </form>

    <p>
        <a href="{{ route('login') }}">Ya tengo cuenta</a>
    </p>
@endsection
