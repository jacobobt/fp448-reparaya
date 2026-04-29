<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Especialidades - ReparaYa</title>
</head>
<body>
    <h1>Especialidades</h1>

    <p>
        <a href="{{ route('admin.dashboard') }}">Panel administrador</a> |
        <a href="{{ route('admin.especialidades.create') }}">Nueva especialidad</a>
    </p>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Precio base</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($especialidades as $especialidad)
            <tr>
                <td>{{ $especialidad->nombre_especialidad }}</td>
                <td>{{ number_format($especialidad->precio, 2) }} €</td>
                <td>
                    <a href="{{ route('admin.especialidades.edit', $especialidad) }}">Editar</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No hay especialidades registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
