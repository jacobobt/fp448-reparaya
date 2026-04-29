<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi agenda - ReparaYa</title>
</head>
<body>
    <h1>Mi agenda</h1>

    <p>
        <a href="{{ route('home') }}">Inicio</a>
    </p>

    <p>
        Técnico: {{ $tecnico->nombre_completo }}
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
            <th>Franja</th>
            <th>Urgencia</th>
            <th>Estado</th>
            <th>Acciones</th>
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
                <td>{{ $incidencia->franja_horaria }}</td>
                <td>{{ $incidencia->tipo_urgencia }}</td>
                <td>{{ $incidencia->estado }}</td>
                <td>
                    @if ($incidencia->estado !== 'Finalizada')
                        <form method="POST" action="{{ route('tecnico.incidencias.finalizar', $incidencia) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Marcar finalizada</button>
                        </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No tienes servicios asignados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
