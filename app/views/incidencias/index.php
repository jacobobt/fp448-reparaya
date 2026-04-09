<h1>Mis avisos</h1>

<?php if (!empty($mensaje)): ?>
    <?php if ($mensaje === 'creada'): ?>
        <div class="mensaje">Aviso creado correctamente.</div>
    <?php elseif ($mensaje === 'cancelada'): ?>
        <div class="mensaje">Aviso cancelado correctamente.</div>
    <?php elseif ($mensaje === 'bloqueada'): ?>
        <div class="error">No puedes cancelar un aviso si faltan menos de 48 horas para la cita.</div>
    <?php elseif ($mensaje === 'no_encontrada'): ?>
        <div class="error">La incidencia no existe o no te pertenece.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="acciones" style="margin-bottom: 16px;">
    <a href="<?php echo BASE_URL; ?>/?page=incidencia_create">Nueva solicitud</a>
</div>

<?php if (!empty($incidencias)): ?>
    <table>
        <thead>
            <tr>
                <th>Localizador</th>
                <th>Servicio</th>
                <th>Descripción</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Fecha</th>
                <th>Franja</th>
                <th>Urgencia</th>
                <th>Estado</th>
                <th>Técnico</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($incidencias as $incidencia): ?>
                <tr>
                    <td><?php echo htmlspecialchars($incidencia['localizador']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['nombre_especialidad']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['telefono_contacto']); ?></td>
                    <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($incidencia['fecha_servicio']))); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['franja_horaria']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['tipo_urgencia']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['estado']); ?></td>
                    <td><?php echo htmlspecialchars($incidencia['tecnico_nombre'] ?? 'Sin asignar'); ?></td>
                    <td>
                        <?php if ($incidencia['estado'] !== 'Cancelada' && $incidencia['estado'] !== 'Finalizada'): ?>
                            <a href="<?php echo BASE_URL; ?>/?page=incidencia_cancelar&id=<?php echo $incidencia['id']; ?>" onclick="return confirm('¿Seguro que quieres cancelar este aviso?');">Cancelar</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="bloque">
        <p>No tienes avisos registrados.</p>
    </div>
<?php endif; ?>