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

        <h2>Datos de la gestora</h2>

        <label>Nombre de la gestora</label><br>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required><br><br>

        <label>Comisión (%)</label><br>
        <input type="number" step="0.01" min="0" max="100" name="comision_porcentaje" value="{{ old('comision_porcentaje', 5) }}" required><br><br>

        <h2>Usuario de acceso</h2>
        <p style="font-size:13px; color:#555;">Se creará un usuario con rol <strong>gestora</strong> para que pueda acceder al panel.</p>

        <label>Nombre del usuario</label><br>
        <input type="text" name="usuario_nombre" value="{{ old('usuario_nombre') }}" required><br><br>

        <label>Email</label><br>
        <input type="email" name="usuario_email" value="{{ old('usuario_email') }}" required><br><br>

        <label>Contraseña</label><br>
        <input type="password" name="usuario_password" required><br><br>

        <button type="submit">Guardar gestora y crear usuario</button>
    </form>
@endsection