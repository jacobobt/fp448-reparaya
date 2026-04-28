<h1>Login</h1>

<?php if (!empty($mensaje)): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="POST" action="<?php echo BASE_URL; ?>/?page=login">
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

    <button type="submit">Entrar</button>
</form>