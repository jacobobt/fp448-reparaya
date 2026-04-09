<?php
$sqlEspecialidades = "SELECT COUNT(*) AS total FROM especialidades";
$stmtEspecialidades = $pdo->query($sqlEspecialidades);
$totalEspecialidades = (int) $stmtEspecialidades->fetch(PDO::FETCH_ASSOC)['total'];

$sqlTecnicos = "SELECT COUNT(*) AS total FROM tecnicos";
$stmtTecnicos = $pdo->query($sqlTecnicos);
$totalTecnicos = (int) $stmtTecnicos->fetch(PDO::FETCH_ASSOC)['total'];

$sqlTecnicosDisponibles = "SELECT COUNT(*) AS total FROM tecnicos WHERE disponible = 1";
$stmtDisponibles = $pdo->query($sqlTecnicosDisponibles);
$totalDisponibles = (int) $stmtDisponibles->fetch(PDO::FETCH_ASSOC)['total'];
?>

<h1><?php echo APP_NAME; ?></h1>
<p class="texto-suave">Aplicación de gestión de reparaciones domésticas desarrollada en PHP sin framework y organizada con una estructura MVC sencilla.</p>

<div class="tarjetas">
    <div class="tarjeta">
        <h3>Servicios</h3>
        <div class="numero"><?php echo $totalEspecialidades; ?></div>
    </div>

    <div class="tarjeta">
        <h3>Técnicos</h3>
        <div class="numero"><?php echo $totalTecnicos; ?></div>
    </div>

    <div class="tarjeta">
        <h3>Técnicos disponibles</h3>
        <div class="numero"><?php echo $totalDisponibles; ?></div>
    </div>
</div>

<div class="bloque">
    <?php if (!empty($_SESSION['usuario'])): ?>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></h2>
        <p class="texto-suave">Has iniciado sesión como <strong><?php echo htmlspecialchars($_SESSION['usuario']['rol']); ?></strong>.</p>

        <?php if ($_SESSION['usuario']['rol'] === 'admin'): ?>
            <div class="acciones">
                <a href="<?php echo BASE_URL; ?>/?page=admin_dashboard">Panel admin</a>
                <a href="<?php echo BASE_URL; ?>/?page=admin_incidencias">Gestionar avisos</a>
                <a href="<?php echo BASE_URL; ?>/?page=admin_calendar">Calendario</a>
                <a href="<?php echo BASE_URL; ?>/?page=tecnicos">Gestionar técnicos</a>
                <a href="<?php echo BASE_URL; ?>/?page=especialidades">Gestionar servicios</a>
            </div>
        <?php elseif ($_SESSION['usuario']['rol'] === 'particular'): ?>
            <div class="acciones">
                <a href="<?php echo BASE_URL; ?>/?page=mis_avisos">Ver mis avisos</a>
                <a href="<?php echo BASE_URL; ?>/?page=incidencia_create">Crear nueva solicitud</a>
                <a class="secundario" href="<?php echo BASE_URL; ?>/?page=profile">Editar perfil</a>
            </div>
        <?php elseif ($_SESSION['usuario']['rol'] === 'tecnico'): ?>
            <div class="acciones">
                <a href="<?php echo BASE_URL; ?>/?page=tecnico_agenda">Ver mi agenda</a>
                <a class="secundario" href="<?php echo BASE_URL; ?>/?page=profile">Editar perfil</a>
            </div>
        <?php else: ?>
            <div class="acciones">
                <a href="<?php echo BASE_URL; ?>/?page=profile">Ir a mi perfil</a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <h2>Acceso al sistema</h2>
        <p class="texto-suave">Regístrate o inicia sesión para acceder a la aplicación.</p>
        <div class="acciones">
            <a href="<?php echo BASE_URL; ?>/?page=login">Iniciar sesión</a>
            <a class="secundario" href="<?php echo BASE_URL; ?>/?page=register">Registrarse</a>
        </div>
    <?php endif; ?>
</div>