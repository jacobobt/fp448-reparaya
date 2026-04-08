<?php
// views/admin/asignar.php
// Variables: $incidencia (array), $tecnicos (array)
?>
<style>
:root { --azul:#185FA5; --azul-dark:#0C447C; --azul-lite:#E6F1FB; --rojo:#A32D2D; --rojo-lite:#FCEBEB; --verde:#3B6D11; --verde-lite:#EAF3DE; --ambar:#854F0B; --ambar-lite:#FAEEDA; --gris:#5F5E5A; --border:rgba(0,0,0,0.12); --r:8px; --r-lg:12px; }
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
.asig-wrap { font-family: -apple-system,'Segoe UI',sans-serif; padding: 1.5rem; max-width: 560px; }
.asig-wrap h1 { font-size: 20px; font-weight: 600; color: var(--azul-dark); margin-bottom: .25rem; }
.asig-wrap .sub { font-size: 13px; color: #888; margin-bottom: 1.5rem; }

/* Tarjeta resumen incidencia */
.inc-card { background: #f8f9fa; border-radius: var(--r-lg); padding: 1rem 1.25rem; margin-bottom: 1.25rem; border-left: 3px solid var(--azul); }
.inc-card .loc { font-size: 12px; font-weight: 600; color: var(--gris); margin-bottom: 6px; }
.inc-card .desc { font-size: 14px; font-weight: 500; color: #1a1a1a; margin-bottom: 4px; }
.inc-card .meta { font-size: 12px; color: #888; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600; }
.b-urg { background: var(--rojo-lite); color: var(--rojo); }
.b-std { background: var(--azul-lite); color: var(--azul-dark); }

/* Formulario */
.card { background: #fff; border: 0.5px solid var(--border); border-radius: var(--r-lg); padding: 1.25rem 1.5rem; }
.card-title { font-size: 11px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 1rem; }
.fg { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
.fg label { font-size: 12px; font-weight: 500; color: #555; }
.fg select { padding: 9px 11px; border: 0.5px solid var(--border); border-radius: var(--r); font-size: 13px; color: #1a1a1a; background: #fff; font-family: inherit; transition: border-color .15s; }
.fg select:focus { outline: none; border-color: var(--azul); box-shadow: 0 0 0 3px rgba(24,95,165,.1); }

/* Lista técnicos con disponibilidad */
.tec-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px; }
.tec-opt { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 0.5px solid var(--border); border-radius: var(--r); cursor: pointer; transition: all .15s; }
.tec-opt:hover { border-color: var(--azul); background: var(--azul-lite); }
.tec-opt input { accent-color: var(--azul); }
.tec-opt input:checked ~ .tec-info { color: var(--azul); }
.tec-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--azul-lite); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: var(--azul); flex-shrink: 0; }
.tec-info { flex: 1; }
.tec-info .tnombre { font-size: 13px; font-weight: 500; }
.tec-info .tesp    { font-size: 11px; color: #888; }
.tec-avisos { font-size: 11px; color: #888; white-space: nowrap; }

.actions { display: flex; gap: 10px; padding-top: 4px; }
.btn-save { padding: 10px 24px; background: var(--azul); color: #fff; border: none; border-radius: var(--r); font-size: 13px; font-weight: 500; cursor: pointer; transition: background .15s; }
.btn-save:hover { background: var(--azul-dark); }
.btn-back { padding: 10px 20px; background: transparent; color: #555; border: 0.5px solid var(--border); border-radius: var(--r); font-size: 13px; cursor: pointer; text-decoration: none; transition: background .15s; }
.btn-back:hover { background: #f5f5f5; }
.btn-desasig { padding: 10px 16px; background: transparent; color: var(--rojo); border: 0.5px solid var(--rojo); border-radius: var(--r); font-size: 13px; cursor: pointer; text-decoration: none; margin-left: auto; transition: background .15s; }
.btn-desasig:hover { background: var(--rojo-lite); }
</style>

<div class="asig-wrap">
    <h1>Asignar técnico</h1>
    <p class="sub">Selecciona el técnico para esta incidencia.</p>

    <!-- Resumen incidencia -->
    <div class="inc-card">
        <div class="loc">
            <?= htmlspecialchars($incidencia['localizador']) ?> &nbsp;
            <span class="badge <?= $incidencia['tipo_urgencia'] === 'Urgente' ? 'b-urg' : 'b-std' ?>">
                <?= htmlspecialchars($incidencia['tipo_urgencia']) ?>
            </span>
        </div>
        <div class="desc"><?= htmlspecialchars($incidencia['descripcion']) ?></div>
        <div class="meta">
            <?= htmlspecialchars($incidencia['especialidad']) ?> &middot;
            <?= htmlspecialchars($incidencia['direccion']) ?> &middot;
            <?= date('d/m/Y H:i', strtotime($incidencia['fecha_servicio'])) ?>
        </div>
        <?php if ($incidencia['tecnico_nombre']): ?>
        <div class="meta" style="margin-top:6px;color:var(--azul);">
            Técnico actual: <strong><?= htmlspecialchars($incidencia['tecnico_nombre']) ?></strong>
        </div>
        <?php endif; ?>
    </div>

    <!-- Formulario -->
    <div class="card">
        <div class="card-title">Técnicos disponibles</div>
        <form method="POST" action="<?= BASE_URL ?>?page=asignar_tecnico">
            <input type="hidden" name="id_incidencia" value="<?= $incidencia['id'] ?>">

            <?php if (empty($tecnicos)): ?>
                <p style="font-size:13px;color:#888;margin-bottom:14px;">No hay técnicos registrados en el sistema.</p>
            <?php else: ?>
                <div class="tec-list">
                    <!-- Opción: sin asignar -->
                    <label class="tec-opt">
                        <input type="radio" name="id_tecnico" value="0"
                            <?= !$incidencia['tecnico_id'] ? 'checked' : '' ?>>
                        <div class="tec-avatar" style="background:#f0f0f0;color:#888;">—</div>
                        <div class="tec-info">
                            <div class="tnombre">Sin asignar</div>
                            <div class="tesp">Deja la incidencia en estado Pendiente</div>
                        </div>
                    </label>

                    <?php foreach ($tecnicos as $t): ?>
                    <?php $iniciales = strtoupper(substr($t['nombre'], 0, 1) . (strpos($t['nombre'], ' ') !== false ? substr(strrchr($t['nombre'], ' '), 1, 1) : '')); ?>
                    <label class="tec-opt">
                        <input type="radio" name="id_tecnico" value="<?= $t['id'] ?>"
                            <?= $incidencia['tecnico_id'] == $t['id'] ? 'checked' : '' ?>>
                        <div class="tec-avatar"><?= $iniciales ?></div>
                        <div class="tec-info">
                            <div class="tnombre"><?= htmlspecialchars($t['nombre']) ?></div>
                            <div class="tesp"><?= htmlspecialchars($t['especialidad'] ?? 'Sin especialidad') ?></div>
                        </div>
                        <div class="tec-avisos">
                            <?= $t['avisos_activos'] ?> activo<?= $t['avisos_activos'] != 1 ? 's' : '' ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="actions">
                <a href="<?= BASE_URL ?>?page=admin_dashboard" class="btn-back">Cancelar</a>
                <button type="submit" class="btn-save">Confirmar asignación</button>
            </div>
        </form>
    </div>
</div>
