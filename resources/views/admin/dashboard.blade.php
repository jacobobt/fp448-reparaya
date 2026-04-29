<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel administrador - ReparaYa</title>
</head>
<body>
    <h1>Panel administrador</h1>

    <p>
        <a href="{{ route('home') }}">Inicio</a>
    </p>

    <p>
        <a href="{{ route('admin.incidencias.index') }}">Gestionar incidencias</a>
    </p>

    <p>
        <a href="{{ route('admin.especialidades.index') }}">Gestionar especialidades</a>
    </p>

    <p>
        <a href="{{ route('admin.tecnicos.index') }}">Gestionar técnicos</a>
    </p>

    <h2>Resumen</h2>

    <ul>
        <li>Usuarios registrados: {{ $totalUsuarios }}</li>
        <li>Técnicos registrados: {{ $totalTecnicos }}</li>
        <li>Incidencias pendientes: {{ $pendientes }}</li>
        <li>Incidencias asignadas: {{ $asignadas }}</li>
        <li>Incidencias finalizadas: {{ $finalizadas }}</li>
        <li>Incidencias canceladas: {{ $canceladas }}</li>
    </ul>

    <h2>Últimas incidencias</h2>

    <table border="1" cellpadding="8">
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Cliente</th>
            <th>Especialidad</th>
            <th>Técnico</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($ultimasIncidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->cliente->nombre ?? '-' }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>{{ $incidencia->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ $incidencia->estado }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No hay incidencias registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
