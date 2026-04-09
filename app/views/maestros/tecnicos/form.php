<h1>
    <?php echo $tecnico ? 'Editar técnico' : 'Nuevo técnico'; ?>
</h1>

<?php if (!empty($error)): ?>
    <p><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" action="<?php echo BASE_URL; ?>/?page=<?php echo $tecnico ? 'tecnico_update&id=' . $tecnico['id'] : 'tecnico_store'; ?>">
    <div>
        <label for="nombre_completo">Nombre completo:</label><br>
        <input
            type="text"
            id="nombre_completo"
            name="nombre_completo"
            required
            value="<?php echo htmlspecialchars($tecnico['nombre_completo'] ?? ''); ?>"
        >
    </div>
    <br>

    <div>
        <label for="usuario_id">Usuario técnico vinculado:</label><br>
        <select id="usuario_id" name="usuario_id">
            <option value="">Sin vincular</option>
            <?php foreach ($usuariosTecnico as $usuario): ?>
                <option
                    value="<?php echo $usuario['id']; ?>"
                    <?php echo (!empty($tecnico['usuario_id']) && (int) $tecnico['usuario_id'] === (int) $usuario['id']) ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($usuario['nombre'] . ' - ' . $usuario['email']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>

    <div>
        <label for="especialidad_id">Especialidad:</label><br>
        <select id="especialidad_id" name="especialidad_id">
            <option value="">Sin especialidad</option>
            <?php foreach ($especialidades as $especialidadItem): ?>
                <option
                    value="<?php echo $especialidadItem['id']; ?>"
                    <?php echo (!empty($tecnico['especialidad_id']) && (int) $tecnico['especialidad_id'] === (int) $especialidadItem['id']) ? 'selected' : ''; ?>
                >
                    <?php echo htmlspecialchars($especialidadItem['nombre_especialidad']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <br>

    <div>
        <label>
            <input
                type="checkbox"
                name="disponible"
                <?php echo (!isset($tecnico['disponible']) || (int) $tecnico['disponible'] === 1) ? 'checked' : ''; ?>
            >
            Técnico disponible
        </label>
    </div>
    <br>

    <button type="submit"><?php echo $tecnico ? 'Guardar cambios' : 'Crear técnico'; ?></button>
    <a href="<?php echo BASE_URL; ?>/?page=tecnicos">Volver</a>
</form>