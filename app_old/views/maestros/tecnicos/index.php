<h1>Gestión de Técnicos</h1>

<?php if (!empty($mensaje)): ?>
    <?php if ($mensaje === 'creado'): ?>
        <div class="mensaje">Técnico creado correctamente.</div>
    <?php elseif ($mensaje === 'actualizado'): ?>
        <div class="mensaje">Técnico actualizado correctamente.</div>
    <?php elseif ($mensaje === 'estado'): ?>
        <div class="mensaje">Disponibilidad del técnico actualizada correctamente.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="acciones" style="margin-bottom: 16px;">
    <a href="<?php echo BASE_URL; ?>/?page=tecnico_create">Nuevo técnico</a>
</div>

<?php if (!empty($tecnicos)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre completo</th>
                <th>Usuario vinculado</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Especialidad</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tecnicos as $tecnico): ?>
                <tr>
                    <td><?php echo $tecnico['id']; ?></td>
                    <td><?php echo htmlspecialchars($tecnico['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($tecnico['usuario_nombre'] ?? 'Sin vincular'); ?></td>
                    <td><?php echo htmlspecialchars($tecnico['usuario_email'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($tecnico['usuario_telefono'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($tecnico['nombre_especialidad'] ?? 'Sin especialidad'); ?></td>
                    <td><?php echo $tecnico['disponible'] ? 'Sí' : 'No'; ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/?page=tecnico_edit&id=<?php echo $tecnico['id']; ?>">Editar</a>
                        |
                        <a href="<?php echo BASE_URL; ?>/?page=tecnico_toggle&id=<?php echo $tecnico['id']; ?>">
                            <?php echo $tecnico['disponible'] ? 'Desactivar' : 'Activar'; ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="bloque">
        <p>No hay técnicos registrados.</p>
    </div>
<?php endif; ?>