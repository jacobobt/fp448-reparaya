<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f7fb;
            color: #1f2937;
        }

        .contenedor {
            max-width: 1180px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #1e3a8a;
            color: white;
            padding: 20px 0;
        }

        header h2 {
            margin: 0 0 10px 0;
        }

        nav a,
        nav span {
            color: white;
            text-decoration: none;
            margin-right: 10px;
            font-size: 15px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .tarjetas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .tarjeta {
            background: white;
            border-radius: 10px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .tarjeta h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .tarjeta .numero {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .bloque {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        .acciones a,
        .boton,
        button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            padding: 10px 14px;
            margin-right: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .acciones a.secundario,
        .boton.secundario {
            background-color: #6b7280;
        }

        .acciones a:hover,
        .boton:hover,
        button:hover {
            opacity: 0.9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th,
        table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background-color: #e5e7eb;
        }

        input,
        select,
        textarea {
            width: 100%;
            max-width: 500px;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .mensaje {
            background-color: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .error {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .texto-suave {
            color: #4b5563;
        }

        .chip {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }

        .chip.urgente {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .chip.estandar {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .chip.estado-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .chip.estado-asignada {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .chip.estado-finalizada {
            background-color: #dcfce7;
            color: #166534;
        }

        .chip.estado-cancelada {
            background-color: #e5e7eb;
            color: #374151;
        }

        .grid-dos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .grid-tres {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .evento {
            border-left: 6px solid #2563eb;
            padding: 14px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 12px;
        }

        .evento.urgente {
            border-left-color: #dc2626;
        }

        .evento.estandar {
            border-left-color: #2563eb;
        }

        .evento h4 {
            margin: 0 0 8px 0;
        }

        .separador-dia {
            margin-top: 24px;
            margin-bottom: 12px;
            font-size: 18px;
            font-weight: bold;
        }

        .enlace-titulo {
            color: #111827;
            text-decoration: none;
        }

        .enlace-titulo:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php require_once APP_PATH . '/views/layouts/header.php'; ?>

    <div class="contenedor">
        <?php require_once $view; ?>
    </div>
</body>
</html>