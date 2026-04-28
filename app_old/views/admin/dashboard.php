<h1>Panel de administración</h1>

<div class="tarjetas">
    <div class="tarjeta">
        <h3>Total avisos</h3>
        <div class="numero"><?php echo (int) ($resumen['total'] ?? 0); ?></div>
    </div>

    <div class="tarjeta">
        <h3>Pendientes</h3>
        <div class="numero"><?php echo (int) ($resumen['pendientes'] ?? 0); ?></div>
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
    <h2>Acciones rápidas</h2>
    <div class="acciones">
        <a href="<?php echo BASE_URL; ?>/?page=admin_incidencia_create">Crear aviso manual</a>
        <a href="<?php echo BASE_URL; ?>/?page=admin_incidencias">Gestionar avisos</a>
        <a href="<?php echo BASE_URL; ?>/?page=admin_calendar">Abrir calendario</a>
        <a href="<?php echo BASE_URL; ?>/?page=tecnicos">Gestionar técnicos</a>
        <a href="<?php echo BASE_URL; ?>/?page=especialidades">Gestionar servicios</a>
    </div>
</div>

<div class="bloque">
    <h2>Últimos avisos</h2>

    <?php if (!empty($incidencias)): ?>
        <table>
            <thead>
                <tr>
                    <th>Localizador</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Urgencia</th>
                    <th>Estado</th>
                    <th>Técnico</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidencias as $incidencia): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($incidencia['localizador']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['cliente_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($incidencia['nombre_especialidad']); ?></td>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay avisos registrados.</p>
    <?php endif; ?>
</div>