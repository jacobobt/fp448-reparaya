<h1>Mi perfil</h1>

<?php if (!empty($mensaje)): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<?php if (!empty($_SESSION['usuario'])): ?>
    <form method="POST" action="<?php echo BASE_URL; ?>/?page=profile">
        <div>
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo $_SESSION['usuario']['nombre']; ?>">
        </div>
        <br>

        <div>
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?php echo $_SESSION['usuario']['email']; ?>">
        </div>
        <br>

        <div>
            <label for="telefono">Teléfono:</label><br>
            <input type="text" id="telefono" name="telefono" value="<?php echo $_SESSION['usuario']['telefono']; ?>">
        </div>
        <br>

        <p><strong>Rol:</strong> <?php echo $_SESSION['usuario']['rol']; ?></p>

        <button type="submit">Guardar cambios</button>
    </form>
<?php else: ?>
    <p>No hay ningún usuario logueado.</p>
<?php endif; ?>