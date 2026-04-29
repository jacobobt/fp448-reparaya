<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis avisos - ReparaYa</title>
</head>
<body>
    <h1>Mis avisos</h1>

    <p>
        <a href="{{ route('home') }}">Inicio</a> |
        <a href="{{ route('incidencias.create') }}">Nuevo aviso</a>
    </p>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Especialidad</th>
            <th>Dirección</th>
            <th>Fecha</th>
            <th>Franja</th>
            <th>Urgencia</th>
            <th>Estado</th>
            <th>Técnico</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($incidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $incidencia->direccion }}</td>
                <td>{{ $incidencia->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ $incidencia->franja_horaria }}</td>
                <td>{{ $incidencia->tipo_urgencia }}</td>
                <td>{{ $incidencia->estado }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>
                    @if ($incidencia->estado !== 'Cancelada')
                        <form method="POST" action="{{ route('incidencias.cancelar', $incidencia) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Cancelar</button>
                        </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">Todavía no tienes avisos registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
