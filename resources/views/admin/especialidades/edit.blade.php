<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar especialidad - ReparaYa</title>
</head>
<body>
    <h1>Editar especialidad</h1>

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

    <form method="POST" action="{{ route('admin.especialidades.update', $especialidad) }}">
        @csrf
        @method('PATCH')

        <label>Nombre</label><br>
        <input type="text" name="nombre_especialidad" value="{{ old('nombre_especialidad', $especialidad->nombre_especialidad) }}" required><br><br>

        <label>Precio base</label><br>
        <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio', $especialidad->precio) }}" required><br><br>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
