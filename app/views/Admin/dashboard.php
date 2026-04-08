<?php
// views/admin/dashboard.php
// Variables disponibles desde el controlador:
// $incidencias, $tecnicos, $especialidades, $stats
?>
<style>
:root {
    --azul:       #185FA5;
    --azul-dark:  #0C447C;
    --azul-lite:  #E6F1FB;
    --rojo:       #A32D2D;
    --rojo-lite:  #FCEBEB;
    --verde:      #3B6D11;
    --verde-lite: #EAF3DE;
    --ambar:      #854F0B;
    --ambar-lite: #FAEEDA;
    --gris:       #5F5E5A;
    --gris-lite:  #F1EFE8;
    --border:     rgba(0,0,0,0.1);
    --r:          8px;
    --r-lg:       12px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
.adm { font-family: -apple-system, 'Segoe UI', sans-serif; padding: 1.5rem; color: #1a1a1a; }

/* Cabecera */
.adm-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 1.5rem; }
.adm-top h1 { font-size: 20px; font-weight: 600; color: var(--azul-dark); }
.adm-top p  { font-size: 13px; color: var(--gris); margin-top: 3px; }
.btn-prim { display: inline-flex; align-items: center; gap: 5px; padding: 9px 18px; background: var(--azul); color: #fff; border: none; border-radius: var(--r); font-size: 13px; font-weight: 500; text-decoration: none; cursor: pointer; transition: background .15s; }
.btn-prim:hover { background: var(--azul-dark); }

/* Flash */
.flash { padding: 10px 16px; border-radius: var(--r); font-size: 13px; margin-bottom: 1rem; border: 0.5px solid; }
.flash-ok  { background: var(--verde-lite); color: var(--verde); border-color: var(--verde); }

/* Stats */
.stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 12px; margin-bottom: 1.5rem; }
.scard { background: #f8f9fa; border-radius: var(--r); padding: 14px 16px; }
.scard .lbl { font-size: 11px; color: var(--gris); text-transform: uppercase; letter-spacing: .04em; margin-bottom: 4px; }
.scard .val { font-size: 26px; font-weight: 600; line-height: 1; color: #1a1a1a; }
.scard.urg .val  { color: var(--rojo); }
.scard.pend .val { color: var(--ambar); }
.scard.asig .val { color: var(--azul); }
.scard.fin .val  { color: var(--verde); }

/* Filtros */
.filtros { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
.filtros select, .filtros input { padding: 7px 10px; border: 0.5px solid var(--border); border-radius: var(--r); font-size: 13px; background: #fff; color: #1a1a1a; }
.filtros input { flex: 1; min-width: 180px; }

/* Tabla */
.twrap { background: #fff; border: 0.5px solid var(--border); border-radius: var(--r-lg); overflow: hidden; }
.thead-bar { display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; border-bottom: 0.5px solid var(--border); }
.thead-bar h2 { font-size: 14px; font-weight: 600; }
.tscroll { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 900px; }
thead th { text-align: left; padding: 10px 14px; font-size: 11px; font-weight: 600; color: var(--gris); background: #f8f9fa; border-bottom: 0.5px solid var(--border); text-transform: uppercase; letter-spacing: .04em; }
tbody td { padding: 11px 14px; border-bottom: 0.5px solid #f0f0f0; vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover td { background: #fafafa; }

/* Badges */
.badge { display: inline-block; padding: 3px 9px; border-radius: 99px; font-size: 11px; font-weight: 600; }
.b-urg  { background: var(--rojo-lite);  color: var(--rojo); }
.b-std  { background: var(--azul-lite);  color: var(--azul-dark); }
.b-pend { background: var(--ambar-lite); color: var(--ambar); }
.b-asig { background: var(--azul-lite);  color: var(--azul-dark); }
.b-fin  { background: var(--verde-lite); color: var(--verde); }
.b-can  { background: var(--gris-lite);  color: var(--gris); }

/* Botones tabla */
.accs { display: flex; gap: 5px; flex-wrap: wrap; }
.bta { padding: 4px 10px; font-size: 11px; font-weight: 500; border: 0.5px solid; border-radius: var(--r); background: transparent; cursor: pointer; text-decoration: none; white-space: nowrap; transition: background .12s; }
.bta-asig { border-color: var(--azul);  color: var(--azul); }
.bta-asig:hover { background: var(--azul-lite); }
.bta-edit { border-color: var(--gris);  color: var(--gris); }
.bta-edit:hover { background: var(--gris-lite); }
.bta-can  { border-color: var(--rojo);  color: var(--rojo); }
.bta-can:hover  { background: var(--rojo-lite); }

.empty { text-align: center; padding: 40px; color: var(--gris); font-size: 13px; }
</style>

<div class="adm">

    <div class="adm-top">
        <div>
            <h1>Panel de administración</h1>
            <p>ReparaYa — Gestión de incidencias</p>
        </div>
        <a href="<?= BASE_URL ?>?page=nuevo_aviso" class="btn-prim">+ Nuevo aviso</a>
    </div>

    <?php
    $msgs = [
        'creado'   => 'Incidencia creada correctamente.',
        'editado'  => 'Incidencia actualizada correctamente.',
        'asignado' => 'Técnico asignado correctamente.',
        'cancelado'=> 'Incidencia cancelada.',
    ];
    if (!empty($_GET['msg']) && isset($msgs[$_GET['msg']])): ?>
        <div class="flash flash-ok"><?= $msgs[$_GET['msg']] ?></div>
    <?php endif; ?>

    <!-- Estadísticas -->
    <div class="stats">
        <div class="scard">      <div class="lbl">Total avisos</div>  <div class="val"><?= $stats['total'] ?></div></div>
        <div class="scard urg">  <div class="lbl">Urgentes</div>      <div class="val"><?= $stats['urgentes'] ?></div></div>
        <div class="scard pend"> <div class="lbl">Pendientes</div>    <div class="val"><?= $stats['pendientes'] ?></div></div>
        <div class="scard asig"> <div class="lbl">Asignadas</div>     <div class="val"><?= $stats['asignadas'] ?></div></div>
        <div class="scard fin">  <div class="lbl">Finalizadas</div>   <div class="val"><?= $stats['finalizadas'] ?></div></div>
        <div class="scard">      <div class="lbl">Técnicos activos</div><div class="val"><?= $stats['tecnicos'] ?></div></div>
    </div>

    <!-- Filtros -->
    <div class="filtros">
        <select id="fil-urg" onchange="filtrar()">
            <option value="">Todos los tipos</option>
            <option value="Urgente">Urgente</option>
            <option value="Estándar">Estándar</option>
        </select>
        <select id="fil-est" onchange="filtrar()">
            <option value="">Todos los estados</option>
            <option value="Pendiente">Pendiente</option>
            <option value="Asignada">Asignada</option>
            <option value="Finalizada">Finalizada</option>
            <option value="Cancelada">Cancelada</option>
        </select>
        <select id="fil-esp" onchange="filtrar()">
            <option value="">Todas las especialidades</option>
            <?php foreach ($especialidades as $e): ?>
                <option value="<?= htmlspecialchars($e['nombre_especialidad']) ?>">
                    <?= htmlspecialchars($e['nombre_especialidad']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" id="fil-q" placeholder="Buscar por cliente o localizador..." oninput="filtrar()">
    </div>

    <!-- Tabla -->
    <div class="twrap">
        <div class="thead-bar">
            <h2>Incidencias</h2>
            <span id="conteo" style="font-size:12px;color:#999;"></span>
        </div>
        <div class="tscroll">
            <table id="tbl">
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Especialidad</th>
                        <th>Descripción</th>
                        <th>Fecha servicio</th>
                        <th>Estado</th>
                        <th>Técnico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($incidencias)): ?>
                    <tr><td colspan="9" class="empty">No hay incidencias registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($incidencias as $i): ?>
                    <?php
                        $badgeEstado = [
                            'Pendiente'  => 'b-pend',
                            'Asignada'   => 'b-asig',
                            'Finalizada' => 'b-fin',
                            'Cancelada'  => 'b-can',
                        ];
                        $clsEst = $badgeEstado[$i['estado']] ?? 'b-can';
                        $activa = !in_array($i['estado'], ['Cancelada', 'Finalizada']);
                    ?>
                    <tr data-urg="<?= htmlspecialchars($i['tipo_urgencia']) ?>"
                        data-est="<?= htmlspecialchars($i['estado']) ?>"
                        data-esp="<?= htmlspecialchars($i['especialidad']) ?>"
                        data-q="<?= strtolower(htmlspecialchars($i['localizador'] . ' ' . $i['cliente_nombre'])) ?>">

                        <td style="font-weight:600;font-size:12px;color:#666;"><?= htmlspecialchars($i['localizador']) ?></td>

                        <td><span class="badge <?= $i['tipo_urgencia'] === 'Urgente' ? 'b-urg' : 'b-std' ?>">
                            <?= htmlspecialchars($i['tipo_urgencia']) ?>
                        </span></td>

                        <td><?= htmlspecialchars($i['cliente_nombre']) ?>
                            <br><span style="font-size:11px;color:#999;"><?= htmlspecialchars($i['cliente_telefono']) ?></span>
                        </td>

                        <td><?= htmlspecialchars($i['especialidad']) ?></td>

                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                            title="<?= htmlspecialchars($i['descripcion']) ?>">
                            <?= htmlspecialchars($i['descripcion']) ?>
                        </td>

                        <td style="font-size:12px;white-space:nowrap;">
                            <?= date('d/m/Y H:i', strtotime($i['fecha_servicio'])) ?>
                        </td>

                        <td><span class="badge <?= $clsEst ?>"><?= htmlspecialchars($i['estado']) ?></span></td>

                        <td style="font-size:12px;color:#555;">
                            <?= $i['tecnico_nombre']
                                ? htmlspecialchars($i['tecnico_nombre'])
                                : '<span style="color:#ccc;">Sin asignar</span>' ?>
                        </td>

                        <td>
                            <div class="accs">
                            <?php if ($activa): ?>
                                <a href="<?= BASE_URL ?>?page=asignar_tecnico&id=<?= $i['id'] ?>" class="bta bta-asig">Asignar</a>
                                <a href="<?= BASE_URL ?>?page=editar_aviso&id=<?= $i['id'] ?>" class="bta bta-edit">Editar</a>
                                <a href="<?= BASE_URL ?>?page=cancelar_aviso&id=<?= $i['id'] ?>"
                                   class="bta bta-can"
                                   onclick="return confirm('¿Cancelar esta incidencia?')">Cancelar</a>
                            <?php else: ?>
                                <span style="font-size:11px;color:#ccc;">—</span>
                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filtrar() {
    const urg = document.getElementById('fil-urg').value.toLowerCase();
    const est = document.getElementById('fil-est').value.toLowerCase();
    const esp = document.getElementById('fil-esp').value.toLowerCase();
    const q   = document.getElementById('fil-q').value.toLowerCase();
    const rows = document.querySelectorAll('#tbl tbody tr[data-urg]');
    let n = 0;
    rows.forEach(tr => {
        const ok = (!urg || tr.dataset.urg.toLowerCase() === urg)
                && (!est || tr.dataset.est.toLowerCase() === est)
                && (!esp || tr.dataset.esp.toLowerCase() === esp)
                && (!q   || tr.dataset.q.includes(q));
        tr.style.display = ok ? '' : 'none';
        if (ok) n++;
    });
    document.getElementById('conteo').textContent = n + ' resultado' + (n !== 1 ? 's' : '');
}
filtrar();
</script>
