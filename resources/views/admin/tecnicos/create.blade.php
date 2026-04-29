<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo técnico - ReparaYa</title>
</head>
<body>
    <h1>Nuevo técnico</h1>

    <p>
        <a href="{{ route('admin.tecnicos.index') }}">Volver</a>
    </p>

    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.tecnicos.store') }}">
        @csrf

        <label>Usuario asociado</label><br>
        <select name="usuario_id">
            <option value="">Sin usuario asociado</option>
            @foreach ($usuarios as $usuario)
                <option value="{{ $usuario->id }}" @selected(old('usuario_id') == $usuario->id)>
                    {{ $usuario->nombre }} - {{ $usuario->email }}
                </option>
            @endforeach
        </select><br><br>

        <label>Nombre completo</label><br>
        <input type="text" name="nombre_completo" value="{{ old('nombre_completo') }}" required><br><br>

        <label>Especialidad</label><br>
        <select name="especialidad_id">
            <option value="">Sin especialidad</option>
            @foreach ($especialidades as $especialidad)
                <option value="{{ $especialidad->id }}" @selected(old('especialidad_id') == $especialidad->id)>
                    {{ $especialidad->nombre_especialidad }}
                </option>
            @endforeach
        </select><br><br>

        <label>Disponible</label><br>
        <select name="disponible" required>
            <option value="1" @selected(old('disponible', '1') == '1')>Sí</option>
            <option value="0" @selected(old('disponible') == '0')>No</option>
        </select><br><br>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>
