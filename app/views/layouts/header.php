<header>
    <h2><?php echo APP_NAME; ?></h2>
    <nav>
        <a href="<?php echo BASE_URL; ?>">Inicio</a> |

        <?php if (!empty($_SESSION['usuario'])): ?>
            <span>Hola, <?php echo $_SESSION['usuario']['nombre']; ?></span> |
            <a href="<?php echo BASE_URL; ?>/?page=profile">Mi perfil</a> |
            <a href="<?php echo BASE_URL; ?>/?page=logout">Cerrar sesión</a>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>/?page=login">Login</a> |
            <a href="<?php echo BASE_URL; ?>/?page=register">Registro</a>
        <?php endif; ?>
    </nav>
    <hr>
</header>