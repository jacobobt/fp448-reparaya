<h1>Registro de usuario</h1>

<?php if (!empty($mensaje)): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <div>
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre">
    </div>
    <br>

    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email">
    </div>
    <br>

    <div>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password">
    </div>
    <br>

    <div>
        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono">
    </div>
    <br>

    <button type="submit">Registrarse</button>
</form>