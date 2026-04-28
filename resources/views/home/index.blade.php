<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ReparaYa - Producto 3</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6f8;
            color: #1f2933;
        }

        header {
            background: #0f766e;
            color: white;
            padding: 24px 40px;
        }

        main {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 24px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #d9e2ec;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            color: #0f766e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border: 1px solid #d9e2ec;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #d9e2ec;
            text-align: left;
        }

        th {
            background: #e6fffa;
        }
    </style>
</head>
<body>
<header>
    <h1>ReparaYa</h1>
    <p>Producto 3 - Migración a Laravel</p>
</header>

<main>
    <section class="stats">
        <div class="card">
            <div class="number">{{ $totalUsuarios }}</div>
            <div>Usuarios registrados</div>
        </div>

        <div class="card">
            <div class="number">{{ $totalTecnicos }}</div>
            <div>Técnicos disponibles</div>
        </div>

        <div class="card">
            <div class="number">{{ $totalIncidencias }}</div>
            <div>Incidencias registradas</div>
        </div>
    </section>

    <h2>Últimas incidencias</h2>

    <table>
        <thead>
        <tr>
            <th>Localizador</th>
            <th>Cliente</th>
            <th>Especialidad</th>
            <th>Técnico</th>
            <th>Estado</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($ultimasIncidencias as $incidencia)
            <tr>
                <td>{{ $incidencia->localizador }}</td>
                <td>{{ $incidencia->cliente->nombre ?? 'Sin cliente' }}</td>
                <td>{{ $incidencia->especialidad->nombre_especialidad ?? 'Sin especialidad' }}</td>
                <td>{{ $incidencia->tecnico->nombre_completo ?? 'Sin asignar' }}</td>
                <td>{{ $incidencia->estado }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay incidencias registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</main>
</body>
</html>
