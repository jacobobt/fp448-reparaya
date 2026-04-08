<?php
// views/admin/nuevo_aviso.php
// Variables: $especialidades, $clientes, $incidencia (null=crear, array=editar), $error
$esEdicion = !empty($incidencia);
$titulo    = $esEdicion ? 'Editar incidencia' : 'Nueva incidencia';
$action    = $esEdicion
    ? BASE_URL . '?page=editar_aviso&id=' . $incidencia['id']
    : BASE_URL . '?page=nuevo_aviso';

// Valores por defecto para el formulario
$val = [
    'tipo_urgencia'   => $incidencia['tipo_urgencia']   ?? 'Estándar',
    'especialidad_id' => $incidencia['especialidad_id'] ?? '',
    'cliente_id'      => $incidencia['cliente_id']      ?? '',
    'descripcion'     => $incidencia['descripcion']     ?? '',
    'direccion'       => $incidencia['direccion']       ?? '',
    'fecha'           => $incidencia ? date('Y-m-d', strtotime($incidencia['fecha_servicio'])) : '',
    'hora'            => $incidencia ? date('H:i',   strtotime($incidencia['fecha_servicio'])) : '09:00',
    'estado'          => $incidencia['estado']          ?? 'Pendiente',
];
?>
<style>
:root { --azul:#185FA5; --azul-dark:#0C447C; --azul-lite:#E6F1FB; --rojo:#A32D2D; --rojo-lite:#FCEBEB; --border:rgba(0,0,0,0.12); --r:8px; --r-lg:12px; }
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
.fw { font-family: -apple-system,'Segoe UI',sans-serif; padding: 1.5rem; max-width: 680px; color: #1a1a1a; }
.fw h1 { font-size: 20px; font-weight: 600; color: var(--azul-dark); margin-bottom: .25rem; }
.fw .sub { font-size: 13px; color: #888; margin-bottom: 1.5rem; }
.card { background: #fff; border: 0.5px solid var(--border); border-radius: var(--r-lg); padding: 1.25rem 1.5rem; margin-bottom: 1rem; }
.card-title { font-size: 11px; font-weight: 600; color: #888; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 1rem; }
.fg { display: flex; flex-direction: column; gap: 5px; margin-bottom: 12px; }
.fg:last-child { margin-bottom: 0; }
.fg label { font-size: 12px; font-weight: 500; color: #555; }
.fg input, .fg select, .fg textarea { padding: 9px 11px; border: 0.5px solid var(--border); border-radius: var(--r); font-size: 13px; color: #1a1a1a; background: #fff; font-family: inherit; transition: border-color .15s; }
.fg input:focus, .fg select:focus, .fg textarea:focus { outline: none; border-color: var(--azul); box-shadow: 0 0 0 3px rgba(24,95,165,.1); }
.fg textarea { resize: vertical; min-height: 80px; }
.frow { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.error { background: var(--rojo-lite); color: var(--rojo); border: 0.5px solid var(--rojo); border-radius: var(--r); padding: 10px 14px; font-size: 13px; margin-bottom: 1rem; }

/* Selector urgencia */
.urg-sel { display: flex; gap: 10px; }
.urg-sel input[type=radio] { display: none; }
.urg-sel label { flex: 1; border: 0.5px solid var(--border); border-radius: var(--r); padding: 10px; text-align: center; cursor: pointer; font-size: 13px; color: #555; font-weight: 500; transition: all .15s; user-select: none; }
.urg-sel input[type=radio]#urg-std:checked ~ label[for=urg-std] { border-color: var(--azul); background: var(--azul-lite); color: var(--azul); }
.urg-sel input[type=radio]#urg-urg:checked ~ label[for=urg-urg] { border-color: var(--rojo); background: var(--rojo-lite); color: var(--rojo); }

.actions { display: flex; gap: 10px; margin-top: .5rem; }
.btn-save { padding: 10px 24px; background: var(--azul); color: #fff; border: none; border-radius: var(--r); font-size: 13px; font-weight: 500; cursor: pointer; transition: background .15s; }
.btn-save:hover { background: var(--azul-dark); }
.btn-back { padding: 10px 20px; background: transparent; color: #555; border: 0.5px solid var(--border); border-radius: var(--r); font-size: 13px; cursor: pointer; text-decoration: none; transition: background .15s; }
.btn-back:hover { background: #f5f5f5; }

@media (max-width: 560px) { .frow { grid-template-columns: 1fr; } }
</style>

<div class="fw">
    <h1><?= $titulo ?></h1>
    <p class="sub"><?= $esEdicion ? 'Modifica los campos de la incidencia.' : 'Rellena todos los campos para crear un nuevo aviso.' ?></p>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= $action ?>">

        <!-- Tipo urgencia -->
        <div class="card">
            <div class="card-title">Tipo de servicio</div>
            <div class="urg-sel">
                <input type="radio" name="tipo_urgencia" id="urg-std" value="Estándar"
                    <?= $val['tipo_urgencia'] === 'Estándar' ? 'checked' : '' ?>>
                <input type="radio" name="tipo_urgencia" id="urg-urg" value="Urgente"
                    <?= $val['tipo_urgencia'] === 'Urgente'  ? 'checked' : '' ?>>
                <label for="urg-std">Estándar</label>
                <label for="urg-urg">&#9888; Urgente (24h)</label>
            </div>
        </div>

        <!-- Datos del servicio -->
        <div class="card">
            <div class="card-title">Datos del servicio</div>

            <?php if (!$esEdicion): ?>
            <div class="fg">
                <label for="cliente_id">Cliente *</label>
                <select name="cliente_id" id="cliente_id" required>
                    <option value="">Selecciona un cliente...</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $val['cliente_id'] == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?> — <?= htmlspecialchars($c['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="frow">
                <div class="fg">
                    <label for="especialidad_id">Especialidad *</label>
                    <select name="especialidad_id" id="especialidad_id" required>
                        <option value="">Selecciona...</option>
                        <?php foreach ($especialidades as $e): ?>
                            <option value="<?= $e['id'] ?>" <?= $val['especialidad_id'] == $e['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nombre_especialidad']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($esEdicion): ?>
                <div class="fg">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado">
                        <?php foreach (['Pendiente','Asignada','Finalizada','Cancelada'] as $est): ?>
                            <option value="<?= $est ?>" <?= $val['estado'] === $est ? 'selected' : '' ?>><?= $est ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>

            <div class="fg">
                <label for="descripcion">Descripción de la avería *</label>
                <textarea name="descripcion" id="descripcion" required
                    placeholder="Describe la avería con el máximo detalle posible..."><?= htmlspecialchars($val['descripcion']) ?></textarea>
            </div>

            <div class="fg">
                <label for="direccion">Dirección completa *</label>
                <input type="text" name="direccion" id="direccion" required
                    placeholder="Calle, número, piso, ciudad..."
                    value="<?= htmlspecialchars($val['direccion']) ?>">
            </div>

            <div class="frow">
                <div class="fg">
                    <label for="fecha_servicio">Fecha del servicio *</label>
                    <input type="date" name="fecha_servicio" id="fecha_servicio" required
                        value="<?= $val['fecha'] ?>"
                        min="<?= date('Y-m-d') ?>">
                </div>
                <div class="fg">
                    <label for="hora_servicio">Hora aproximada</label>
                    <input type="time" name="hora_servicio" id="hora_servicio"
                        value="<?= $val['hora'] ?>">
                </div>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn-save">
                <?= $esEdicion ? 'Guardar cambios' : 'Crear incidencia' ?>
            </button>
            <a href="<?= BASE_URL ?>?page=admin_dashboard" class="btn-back">Cancelar</a>
        </div>
    </form>
</div>
