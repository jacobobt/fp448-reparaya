<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva especialidad - ReparaYa</title>
</head>
<body>
    <h1>Nueva especialidad</h1>

    <p>
        <a href="{{ route('admin.especialidades.index') }}">Volver</a>
    </p>

    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.especialidades.store') }}">
        @csrf

        <label>Nombre</label><br>
        <input type="text" name="nombre_especialidad" value="{{ old('nombre_especialidad') }}" required><br><br>

        <label>Precio base</label><br>
        <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio') }}" required><br><br>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>
