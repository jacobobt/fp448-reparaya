<h1>Mi agenda de trabajo</h1>

<?php if (empty($tecnico)): ?>
    <div class="error">
        Tu usuario no está vinculado a ningún técnico del sistema. Pide al administrador que te asocie en el maestro de técnicos.
    </div>
<?php else: ?>
    <div class="tarjetas">
        <div class="tarjeta">
            <h3>Técnico</h3>
            <div class="numero" style="font-size: 20px;"><?php echo htmlspecialchars($tecnico['nombre_completo']); ?></div>
            <p class="texto-suave"><?php echo htmlspecialchars($tecnico['nombre_especialidad'] ?? 'Sin especialidad'); ?></p>
        </div>

        <div class="tarjeta">
            <h3>Total avisos</h3>
            <div class="numero"><?php echo (int) ($resumen['total'] ?? 0); ?></div>
        </div>

        <div class="tarjeta">
            <h3>Asignadas</h3>
            <div class="numero"><?php echo (int) ($resumen['asignadas'] ?? 0); ?></div>
        </div>

        <div class="tarjeta">
            <h3>Urgentes</h3>
            <div class="numero"><?php echo (int) ($resumen['urgentes'] ?? 0); ?></div>
        </div>
    </div>

    <div class="bloque">
        <h2>Próximos avisos</h2>

        <?php if (!empty($incidencias)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Servicio</th>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Fecha</th>
                        <th>Franja</th>
                        <th>Urgencia</th>
                        <th>Estado</th>
                        <th>Dirección</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($incidencias as $incidencia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($incidencia['localizador']); ?></td>
                            <td><?php echo htmlspecialchars($incidencia['nombre_especialidad']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($incidencia['cliente_nombre']); ?><br>
                                <span class="texto-suave"><?php echo htmlspecialchars($incidencia['cliente_email']); ?></span>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($incidencia['telefono_contacto'] ?: ($incidencia['cliente_telefono'] ?? '-')); ?>
                            </td>
                            <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($incidencia['fecha_servicio']))); ?></td>
                            <td><?php echo htmlspecialchars($incidencia['franja_horaria']); ?></td>
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
                            <td><?php echo htmlspecialchars($incidencia['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($incidencia['descripcion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes avisos asignados.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>