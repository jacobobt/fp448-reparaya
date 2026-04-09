<h1>Gestión de Servicios</h1>

<?php if (!empty($mensaje)): ?>
    <?php if ($mensaje === 'creada'): ?>
        <p>Especialidad creada correctamente.</p>
    <?php elseif ($mensaje === 'actualizada'): ?>
        <p>Especialidad actualizada correctamente.</p>
    <?php elseif ($mensaje === 'eliminada'): ?>
        <p>Especialidad eliminada correctamente.</p>
    <?php elseif ($mensaje === 'bloqueada'): ?>
        <p>No se puede eliminar la especialidad porque tiene técnicos o incidencias asociadas.</p>
    <?php endif; ?>
<?php endif; ?>

<p>
    <a href="<?php echo BASE_URL; ?>/?page=especialidad_create">Nuevo servicio</a>
</p>

<?php if (!empty($especialidades)): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del servicio</th>
                <th>Total técnicos</th>
                <th>Total incidencias</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($especialidades as $especialidad): ?>
                <tr>
                    <td><?php echo $especialidad['id']; ?></td>
                    <td><?php echo htmlspecialchars($especialidad['nombre_especialidad']); ?></td>
                    <td><?php echo (int) $especialidad['total_tecnicos']; ?></td>
                    <td><?php echo (int) $especialidad['total_incidencias']; ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/?page=especialidad_edit&id=<?php echo $especialidad['id']; ?>">Editar</a>
                        |
                        <a href="<?php echo BASE_URL; ?>/?page=especialidad_delete&id=<?php echo $especialidad['id']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este servicio?');">
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No hay servicios registrados.</p>
<?php endif; ?>