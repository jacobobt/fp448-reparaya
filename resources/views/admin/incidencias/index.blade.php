<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Incidencias - Admin ReparaYa</title>
</head>
<body>
    <h1>Gestión de incidencias</h1>

    <p>
        <a href="{{ route('admin.dashboard') }}">Panel administrador</a> |
        <a href="{{ route('home') }}">Inicio</a>
    </p>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Cliente</th>
            <th>Teléfono</th>
            <th>Especialidad</th>
            <th>Dirección</th>
            <th>Fecha</th>
            <th>Urgencia</th>
            <th>Técnico</th>
            <th>Estado</th>
            <th>Cambiar estado</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->cliente->nombre ?? '-' }}</td>
                <td>{{ $incidencia->telefono_contacto }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $incidencia->direccion }}</td>
                <td>{{ $incidencia->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ $incidencia->tipo_urgencia }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>{{ $incidencia->estado }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.incidencias.estado', $incidencia) }}">
                        @csrf
                        @method('PATCH')

                        <select name="estado">
                            @foreach (['Pendiente', 'Asignada', 'Finalizada', 'Cancelada'] as $estado)
                                <option value="{{ $estado }}" @selected($incidencia->estado === $estado)>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit">Guardar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No hay incidencias registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
