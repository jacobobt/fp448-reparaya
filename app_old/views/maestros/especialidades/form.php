<h1><?php echo $especialidad ? 'Editar servicio' : 'Nuevo servicio'; ?></h1>

<?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="bloque">
    <form method="POST" action="<?php echo BASE_URL; ?>/?page=<?php echo $especialidad ? 'especialidad_update&id=' . $especialidad['id'] : 'especialidad_store'; ?>">
        <div>
            <label for="nombre_especialidad">Nombre del servicio:</label><br>
            <input type="text" id="nombre_especialidad" name="nombre_especialidad" required value="<?php echo htmlspecialchars($especialidad['nombre_especialidad'] ?? ''); ?>">
        </div>
        <br>
        <div>
            <label for="precio">Precio:</label><br>
            <input type="number" id="precio" name="precio" step="0.01" min="0" required value="<?php echo isset($especialidad['precio']) ? htmlspecialchars($especialidad['precio']) : '0.00'; ?>">
        </div>
        <br>
        <button type="submit"><?php echo $especialidad ? 'Guardar cambios' : 'Crear servicio'; ?></button>
        <a class="boton secundario" href="<?php echo BASE_URL; ?>/?page=especialidades">Volver</a>
    </form>
</div>