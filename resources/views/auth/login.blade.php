@extends('layouts.app')

@section('title', 'Login - ReparaYa')

@section('content')
    <h1>Iniciar sesión</h1>

    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required><br><br>

        <label>Contraseña</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Entrar</button>
    </form>

    <p>
        <a href="{{ route('register') }}">Crear cuenta</a>
    </p>
@endsection
