<h1>Gestión de Servicios</h1>

<?php if (!empty($mensaje)): ?>
    <?php if ($mensaje === 'creada'): ?>
        <div class="mensaje">Servicio creado correctamente.</div>
    <?php elseif ($mensaje === 'actualizada'): ?>
        <div class="mensaje">Servicio actualizado correctamente.</div>
    <?php elseif ($mensaje === 'eliminada'): ?>
        <div class="mensaje">Servicio eliminado correctamente.</div>
    <?php elseif ($mensaje === 'bloqueada'): ?>
        <div class="error">No se puede eliminar el servicio porque tiene técnicos o incidencias asociadas.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="acciones" style="margin-bottom: 16px;">
    <a href="<?php echo BASE_URL; ?>/?page=especialidad_create">Nuevo servicio</a>
</div>

<?php if (!empty($especialidades)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del servicio</th>
                <th>Total técnicos</th>
                <th>Total incidencias</th>
                <th>Precio</th>
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
                    <td><?php echo number_format($especialidad['precio'], 2); ?> €</td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/?page=especialidad_edit&id=<?php echo $especialidad['id']; ?>">Editar</a>
                        |
                        <a href="<?php echo BASE_URL; ?>/?page=especialidad_delete&id=<?php echo $especialidad['id']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este servicio?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="bloque">
        <p>No hay servicios registrados.</p>
    </div>
<?php endif; ?>