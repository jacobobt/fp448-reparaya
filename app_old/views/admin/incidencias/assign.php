<h1>Asignar técnico</h1>

<?php if (!empty($error)): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="bloque">
    <p><strong>Localizador:</strong> <?php echo htmlspecialchars($incidencia['localizador']); ?></p>
    <p><strong>Fecha:</strong> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($incidencia['fecha_servicio']))); ?></p>
    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($incidencia['descripcion']); ?></p>

    <form method="POST" action="<?php echo BASE_URL; ?>/?page=admin_incidencia_guardar_asignacion&id=<?php echo $incidencia['id']; ?>">
        <div>
            <label for="tecnico_id">Técnico:</label><br>
            <select id="tecnico_id" name="tecnico_id">
                <option value="">Sin asignar</option>
                <?php foreach ($tecnicos as $tecnico): ?>
                    <option value="<?php echo $tecnico['id']; ?>" <?php echo ((string) ($incidencia['tecnico_id'] ?? '') === (string) $tecnico['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($tecnico['nombre_completo'] . ' - ' . ($tecnico['nombre_especialidad'] ?? 'Sin especialidad')); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <button type="submit">Guardar asignación</button>
        <a class="boton secundario" href="<?php echo BASE_URL; ?>/?page=admin_incidencias">Volver</a>
    </form>
</div>