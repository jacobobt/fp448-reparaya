<h1>
    <?php echo $especialidad ? 'Editar servicio' : 'Nuevo servicio'; ?>
</h1>

<?php if (!empty($error)): ?>
    <p><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" action="<?php echo BASE_URL; ?>/?page=<?php echo $especialidad ? 'especialidad_update&id=' . $especialidad['id'] : 'especialidad_store'; ?>">
    <div>
        <label for="nombre_especialidad">Nombre del servicio:</label><br>
        <input
            type="text"
            id="nombre_especialidad"
            name="nombre_especialidad"
            required
            value="<?php echo htmlspecialchars($especialidad['nombre_especialidad'] ?? ''); ?>"
        >
    </div>
    <br>

    <button type="submit"><?php echo $especialidad ? 'Guardar cambios' : 'Crear servicio'; ?></button>
    <a href="<?php echo BASE_URL; ?>/?page=especialidades">Volver</a>
</form>