<h1>Gestión de avisos</h1>

<?php if (!empty($mensaje)): ?>
    <?php if ($mensaje === 'creada'): ?>
        <div class="mensaje">Aviso creado correctamente.</div>
    <?php elseif ($mensaje === 'actualizada'): ?>
        <div class="mensaje">Aviso actualizado correctamente.</div>
    <?php elseif ($mensaje === 'cancelada'): ?>
        <div class="mensaje">Aviso cancelado correctamente.</div>
    <?php elseif ($mensaje === 'asignada'): ?>
        <div class="mensaje">Asignación guardada correctamente.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="acciones" style="margin-bottom: 16px;">
    <a href="<?php echo BASE_URL; ?>/?page=admin_incidencia_create">Crear aviso manual</a>
    <a class="secundario" href="<?php echo BASE_URL; ?>/?page=admin_calendar">Ver calendario</a>
</div>

<?php if (!empty($incidencias)): ?>
    <table>
        <thead>
            <tr>
                <th>Localizador</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Urgencia</th>
                <th>Estado</th>
                <th>Técnico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($incidencias as $incidencia): ?>
                <tr>
                    <td><?php echo htmlspecialchars($incidencia['localizador']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($incidencia['cliente_nombre']); ?><br>
                        <span class="texto-suave"><?php echo htmlspecialchars($incidencia['cliente_email']); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($incidencia['nombre_especialidad']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($incidencia['fecha_servicio']))); ?></td>
                    <td>
                        <span class="chip <?php echo $incidencia['tipo_urgencia'] === 'Urgente' ? 'urgente' : 'estandar'; ?>">
                            <?php echo htmlspecialchars($incidencia['tipo_urgencia']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="chip <?php echo 'estado-' . strtolower($incidencia['estado']); ?>">
                            <?php echo htmlspecialchars($incidencia['estado']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($incidencia['tecnico_nombre'] ?? 'Sin asignar'); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/?page=admin_incidencia_edit&id=<?php echo $incidencia['id']; ?>">Editar</a>
                        |
                        <a href="<?php echo BASE_URL; ?>/?page=admin_incidencia_asignar&id=<?php echo $incidencia['id']; ?>">Asignar</a>
                        |
                        <a href="<?php echo BASE_URL; ?>/?page=admin_incidencia_cancel&id=<?php echo $incidencia['id']; ?>" onclick="return confirm('¿Seguro que quieres cancelar este aviso?');">Cancelar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="bloque">
        <p>No hay avisos registrados.</p>
    </div>
<?php endif; ?>