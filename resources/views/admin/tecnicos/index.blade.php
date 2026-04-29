<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Técnicos - ReparaYa</title>
</head>
<body>
    <h1>Técnicos</h1>

    <p>
        <a href="{{ route('admin.dashboard') }}">Panel administrador</a> |
        <a href="{{ route('admin.tecnicos.create') }}">Nuevo técnico</a>
    </p>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>Nombre completo</th>
            <th>Usuario asociado</th>
            <th>Especialidad</th>
            <th>Disponible</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($tecnicos as $tecnico)
            <tr>
                <td>{{ $tecnico->nombre_completo }}</td>
                <td>{{ $tecnico->usuario->email ?? 'Sin usuario' }}</td>
                <td>{{ $tecnico->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</td>
                <td>{{ $tecnico->disponible ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.tecnicos.edit', $tecnico) }}">Editar</a>

                    <form method="POST" action="{{ route('admin.tecnicos.disponibilidad', $tecnico) }}" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit">
                            {{ $tecnico->disponible ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay técnicos registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
