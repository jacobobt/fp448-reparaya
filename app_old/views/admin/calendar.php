<h1>Calendario de avisos</h1>

<div class="acciones" style="margin-bottom: 16px;">
    <a href="<?php echo BASE_URL; ?>/?page=admin_calendar&mode=month&date=<?php echo htmlspecialchars($inicio->format('Y-m-d')); ?>">Vista mensual</a>
    <a href="<?php echo BASE_URL; ?>/?page=admin_calendar&mode=week&date=<?php echo htmlspecialchars($inicio->format('Y-m-d')); ?>">Vista semanal</a>
    <a href="<?php echo BASE_URL; ?>/?page=admin_calendar&mode=day&date=<?php echo htmlspecialchars($inicio->format('Y-m-d')); ?>">Vista diaria</a>
</div>

<div class="bloque">
    <div class="acciones" style="margin-bottom: 16px;">
        <a class="secundario" href="<?php echo BASE_URL; ?>/?page=admin_calendar&mode=<?php echo htmlspecialchars($modoActual); ?>&date=<?php echo htmlspecialchars($fechaAnterior); ?>">Anterior</a>
        <a class="secundario" href="<?php echo BASE_URL; ?>/?page=admin_calendar&mode=<?php echo htmlspecialchars($modoActual); ?>&date=<?php echo htmlspecialchars(date('Y-m-d')); ?>">Hoy</a>
        <a class="secundario" href="<?php echo BASE_URL; ?>/?page=admin_calendar&mode=<?php echo htmlspecialchars($modoActual); ?>&date=<?php echo htmlspecialchars($fechaSiguiente); ?>">Siguiente</a>
    </div>

    <h2><?php echo htmlspecialchars($tituloRango); ?></h2>

    <?php if (!empty($eventos)): ?>
        <?php
        $ultimoDia = '';
        foreach ($eventos as $evento):
            $diaEvento = date('Y-m-d', strtotime($evento['fecha_servicio']));
            if ($diaEvento !== $ultimoDia):
                $ultimoDia = $diaEvento;
        ?>
            <div class="separador-dia"><?php echo htmlspecialchars(date('d/m/Y', strtotime($evento['fecha_servicio']))); ?></div>
        <?php endif; ?>

            <div class="evento <?php echo $evento['tipo_urgencia'] === 'Urgente' ? 'urgente' : 'estandar'; ?>">
                <h4>
                    <a class="enlace-titulo" href="<?php echo BASE_URL; ?>/?page=admin_incidencia_edit&id=<?php echo $evento['id']; ?>">
                        <?php echo htmlspecialchars($evento['localizador'] . ' - ' . $evento['nombre_especialidad']); ?>
                    </a>
                </h4>
                <p><strong>Hora:</strong> <?php echo htmlspecialchars(date('H:i', strtotime($evento['fecha_servicio']))); ?> | <strong>Franja:</strong> <?php echo htmlspecialchars($evento['franja_horaria']); ?></p>
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($evento['cliente_nombre']); ?></p>
                <p><strong>Técnico:</strong> <?php echo htmlspecialchars($evento['tecnico_nombre'] ?? 'Sin asignar'); ?></p>
                <p><strong>Dirección:</strong> <?php echo htmlspecialchars($evento['direccion']); ?></p>
                <p>
                    <span class="chip <?php echo $evento['tipo_urgencia'] === 'Urgente' ? 'urgente' : 'estandar'; ?>">
                        <?php echo htmlspecialchars($evento['tipo_urgencia']); ?>
                    </span>
                    <span class="chip <?php echo 'estado-' . strtolower($evento['estado']); ?>">
                        <?php echo htmlspecialchars($evento['estado']); ?>
                    </span>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay avisos en este rango de fechas.</p>
    <?php endif; ?>
</div>