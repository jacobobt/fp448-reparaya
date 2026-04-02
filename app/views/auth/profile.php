<h1>Mi perfil</h1>

<?php if (!empty($_SESSION['usuario'])): ?>
    <p><strong>Nombre:</strong> <?php echo $_SESSION['usuario']['nombre']; ?></p>
    <p><strong>Email:</strong> <?php echo $_SESSION['usuario']['email']; ?></p>
    <p><strong>Teléfono:</strong> <?php echo $_SESSION['usuario']['telefono']; ?></p>
    <p><strong>Rol:</strong> <?php echo $_SESSION['usuario']['rol']; ?></p>
<?php else: ?>
    <p>No hay ningún usuario logueado.</p>
<?php endif; ?>