<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'ReparaYa')</title>
    <style>
        body { margin:0; font-family:Arial,sans-serif; background:#f3f6f8; color:#1f2933; }
        header { background:#0f766e; color:white; padding:24px 40px; }
        header h1 { margin:0; }
        header p { margin-bottom:0; }
        nav { display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-top:16px; }
        nav a, nav button { background:white; color:#0f766e; border:none; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:bold; cursor:pointer; }
        nav span { font-weight:bold; }
        main { max-width:1200px; margin:32px auto; padding:0 24px; }
        .stats { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:32px; }
        .card { background:white; padding:20px; border-radius:8px; border:1px solid #d9e2ec; }
        .number { font-size:32px; font-weight:bold; color:#0f766e; }
        table { width:100%; border-collapse:collapse; background:white; border:1px solid #d9e2ec; margin-top:16px; }
        th, td { padding:12px; border-bottom:1px solid #d9e2ec; text-align:left; vertical-align:top; }
        th { background:#e6fffa; }
        input, select, textarea { width:100%; max-width:420px; padding:8px; border:1px solid #bcccdc; border-radius:6px; }
        textarea { min-height:90px; }
        button, .button { background:#0f766e; color:white; border:none; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:bold; cursor:pointer; }
        .actions { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px; }
        .success { color:#047857; font-weight:bold; }
        .error { color:#b91c1c; font-weight:bold; }
    </style>
    @stack('styles')
</head>
<body>
<header>
    <h1>ReparaYa</h1>
    <p>Producto 3 - Laravel</p>
    <nav>
        <a href="{{ route('home') }}">Inicio</a>
        @auth
            <span>Hola, {{ auth()->user()->nombre }} ({{ auth()->user()->rol }})</span>
            <a href="{{ route('profile.show') }}">Mi perfil</a>

            @if (auth()->user()->rol === 'admin')
                <a href="{{ route('admin.dashboard') }}">Panel administrador</a>
                <a href="{{ route('admin.calendario') }}">📅 Calendario</a>
            @endif

            @if (auth()->user()->rol === 'tecnico')
                <a href="{{ route('tecnico.agenda') }}">Mi agenda</a>
            @endif

            @if (auth()->user()->rol === 'particular')
                <a href="{{ route('incidencias.index') }}">Mis avisos</a>
                <a href="{{ route('incidencias.create') }}">Nuevo aviso</a>
            @endif

            @if (auth()->user()->rol === 'gestora')
                <a href="{{ route('gestora.dashboard') }}">Panel gestora</a>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('login') }}">Iniciar sesión</a>
            <a href="{{ route('register') }}">Registro</a>
        @endauth
    </nav>
</header>
<main>
    @yield('content')
</main>
@stack('scripts')
</body>
</html>
