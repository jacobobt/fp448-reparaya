<?php
// views/admin/calendario.php
// Variable disponible: $incidencias (array de todas las incidencias)

// Agrupar incidencias por fecha (YYYY-MM-DD)
$porFecha = [];
foreach ($incidencias as $i) {
    $fecha = date('Y-m-d', strtotime($i['fecha_servicio']));
    $porFecha[$fecha][] = $i;
}

// Mes actual (o el que viene por GET)
$hoy    = new DateTime();
$mesNum = (int) ($_GET['mes'] ?? $hoy->format('n'));
$anyo   = (int) ($_GET['anyo'] ?? $hoy->format('Y'));

// Navegar entre meses
if ($mesNum < 1)  { $mesNum = 12; $anyo--; }
if ($mesNum > 12) { $mesNum = 1;  $anyo++; }

$primerDia   = new DateTime("$anyo-$mesNum-01");
$diasEnMes   = (int) $primerDia->format('t');
$diaSemana   = (int) $primerDia->format('N'); // 1=Lun … 7=Dom
$hoyStr      = $hoy->format('Y-m-d');

// URLs navegación
$mesAnterior = $mesNum - 1; $anyoAnt = $anyo;
if ($mesAnterior < 1) { $mesAnterior = 12; $anyoAnt--; }
$mesSiguiente = $mesNum + 1; $anyoSig = $anyo;
if ($mesSiguiente > 12) { $mesSiguiente = 1; $anyoSig++; }

$meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
?>
<style>
:root { --azul:#185FA5; --azul-dark:#0C447C; --azul-lite:#E6F1FB; --rojo:#A32D2D; --rojo-lite:#FCEBEB; --verde:#3B6D11; --verde-lite:#EAF3DE; --ambar:#854F0B; --gris:#5F5E5A; --border:rgba(0,0,0,0.1); --r:8px; --r-lg:12px; }
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
.cal-wrap { font-family: -apple-system,'Segoe UI',sans-serif; padding: 1.5rem; color: #1a1a1a; }

/* Cabecera */
.cal-top { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 1.25rem; }
.cal-top h1 { font-size: 20px; font-weight: 600; color: var(--azul-dark); }
.cal-nav { display: flex; align-items: center; gap: 8px; }
.cal-nav a { display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border: 0.5px solid var(--border); border-radius: var(--r); text-decoration: none; color: #555; font-size: 16px; transition: background .12s; }
.cal-nav a:hover { background: #f0f0f0; }
.cal-nav .mes-actual { font-size: 15px; font-weight: 600; color: #1a1a1a; min-width: 160px; text-align: center; }

/* Leyenda */
.leyenda { display: flex; gap: 16px; margin-bottom: 1rem; flex-wrap: wrap; }
.leyenda span { font-size: 12px; color: var(--gris); display: flex; align-items: center; gap: 6px; }
.leg-dot { width: 10px; height: 10px; border-radius: 2px; display: inline-block; }

/* Grid del calendario */
.cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
.dia-header { text-align: center; font-size: 11px; font-weight: 600; color: var(--gris); padding: 6px 0; text-transform: uppercase; letter-spacing: .03em; }
.cel {
    min-height: 90px;
    background: #fff;
    border: 0.5px solid var(--border);
    border-radius: var(--r);
    padding: 5px 6px;
    vertical-align: top;
    transition: border-color .12s;
}
.cel:hover { border-color: #aaa; }
.cel.vacio { background: #f8f9fa; border-color: transparent; }
.cel.hoy   { border-color: var(--azul); border-width: 1.5px; }
.num-dia { font-size: 11px; color: #bbb; margin-bottom: 4px; font-weight: 500; }
.cel.hoy .num-dia { color: var(--azul); font-weight: 700; }
.cel.tiene-eventos { background: #fafeff; }

/* Eventos */
.evento {
    font-size: 10px; padding: 2px 5px; border-radius: 3px;
    margin-bottom: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    cursor: default; display: block;
    max-width: 100%;
}
.ev-urg { background: var(--rojo-lite); color: var(--rojo); }
.ev-std { background: var(--azul-lite); color: var(--azul-dark); }
.ev-fin { background: var(--verde-lite); color: var(--verde); }
.ev-can { background: #f0f0f0; color: #999; text-decoration: line-through; }
.ev-mas { font-size: 10px; color: #999; padding: 0 5px; }

/* Panel lateral detalle (modal simple) */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 100; align-items: center; justify-content: center; }
.modal-overlay.open { display: flex; }
.modal { background: #fff; border-radius: var(--r-lg); padding: 1.5rem; width: min(480px, 92vw); max-height: 80vh; overflow-y: auto; }
.modal h2 { font-size: 16px; font-weight: 600; margin-bottom: 1rem; }
.modal-close { float: right; background: none; border: none; font-size: 20px; cursor: pointer; color: #888; line-height: 1; margin-top: -4px; }
.inc-row { border-bottom: 0.5px solid #f0f0f0; padding: 10px 0; }
.inc-row:last-child { border-bottom: none; }
.inc-row .loc { font-size: 11px; color: #888; }
.inc-row .desc { font-size: 13px; font-weight: 500; margin: 2px 0; }
.inc-row .meta { font-size: 12px; color: #888; }
.badge { display: inline-block; padding: 2px 7px; border-radius: 99px; font-size: 10px; font-weight: 600; }
.b-urg { background: var(--rojo-lite); color: var(--rojo); }
.b-std { background: var(--azul-lite); color: var(--azul-dark); }
</style>

<div class="cal-wrap">

    <!-- Cabecera -->
    <div class="cal-top">
        <h1>Calendario de trabajo</h1>
        <div class="cal-nav">
            <a href="?page=admin_calendario&mes=<?= $mesAnterior ?>&anyo=<?= $anyoAnt ?>" title="Mes anterior">&#8592;</a>
            <span class="mes-actual"><?= $meses[$mesNum] ?> <?= $anyo ?></span>
            <a href="?page=admin_calendario&mes=<?= $mesSiguiente ?>&anyo=<?= $anyoSig ?>" title="Mes siguiente">&#8594;</a>
        </div>
    </div>

    <!-- Leyenda -->
    <div class="leyenda">
        <span><span class="leg-dot" style="background:var(--rojo-lite);border:1px solid var(--rojo);"></span>Urgente</span>
        <span><span class="leg-dot" style="background:var(--azul-lite);border:1px solid var(--azul);"></span>Estándar</span>
        <span><span class="leg-dot" style="background:var(--verde-lite);border:1px solid var(--verde);"></span>Finalizada</span>
        <span><span class="leg-dot" style="background:#f0f0f0;border:1px solid #ccc;"></span>Cancelada</span>
    </div>

    <!-- Días de la semana -->
    <div class="cal-grid">
        <?php foreach (['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $d): ?>
            <div class="dia-header"><?= $d ?></div>
        <?php endforeach; ?>
    </div>

    <div style="height:4px;"></div>

    <!-- Celdas del mes -->
    <div class="cal-grid">
        <!-- Celdas vacías antes del día 1 -->
        <?php for ($v = 1; $v < $diaSemana; $v++): ?>
            <div class="cel vacio"></div>
        <?php endfor; ?>

        <!-- Días del mes -->
        <?php for ($d = 1; $d <= $diasEnMes; $d++):
            $fechaStr = sprintf('%04d-%02d-%02d', $anyo, $mesNum, $d);
            $eventos  = $porFecha[$fechaStr] ?? [];
            $esHoy    = ($fechaStr === $hoyStr);
            $clases   = 'cel' . ($esHoy ? ' hoy' : '') . (!empty($eventos) ? ' tiene-eventos' : '');
        ?>
            <div class="<?= $clases ?>">
                <div class="num-dia"><?= $d ?></div>

                <?php
                $mostrar = array_slice($eventos, 0, 3);
                foreach ($mostrar as $ev):
                    $clsEv = match(true) {
                        $ev['estado'] === 'Finalizada' => 'ev-fin',
                        $ev['estado'] === 'Cancelada'  => 'ev-can',
                        $ev['tipo_urgencia'] === 'Urgente' => 'ev-urg',
                        default => 'ev-std',
                    };
                ?>
                    <span class="evento <?= $clsEv ?>"
                          title="<?= htmlspecialchars($ev['localizador'] . ': ' . $ev['descripcion']) ?>"
                          onclick='abrirModal(<?= htmlspecialchars(json_encode($ev), ENT_QUOTES) ?>)'>
                        <?= htmlspecialchars($ev['localizador']) ?>
                    </span>
                <?php endforeach; ?>

                <?php if (count($eventos) > 3): ?>
                    <span class="ev-mas">+<?= count($eventos) - 3 ?> más</span>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
</div>

<!-- Modal detalle -->
<div class="modal-overlay" id="modal" onclick="if(event.target===this)cerrarModal()">
    <div class="modal">
        <button class="modal-close" onclick="cerrarModal()">&#215;</button>
        <h2 id="modal-titulo">Detalle</h2>
        <div id="modal-body"></div>
    </div>
</div>

<script>
function abrirModal(ev) {
    document.getElementById('modal-titulo').textContent = ev.localizador + ' — ' + ev.especialidad;
    const badge = ev.tipo_urgencia === 'Urgente'
        ? '<span class="badge b-urg">Urgente</span>'
        : '<span class="badge b-std">Estándar</span>';

    document.getElementById('modal-body').innerHTML = `
        <div class="inc-row">
            <div class="loc">${ev.localizador} &nbsp; ${badge}</div>
            <div class="desc">${ev.descripcion}</div>
            <div class="meta" style="margin-top:8px;">
                <strong>Fecha:</strong> ${formatFecha(ev.fecha_servicio)}<br>
                <strong>Dirección:</strong> ${ev.direccion}<br>
                <strong>Cliente:</strong> ${ev.cliente_nombre} (${ev.cliente_telefono})<br>
                <strong>Técnico:</strong> ${ev.tecnico_nombre || 'Sin asignar'}<br>
                <strong>Estado:</strong> ${ev.estado}
            </div>
        </div>
    `;
    document.getElementById('modal').classList.add('open');
}

function cerrarModal() {
    document.getElementById('modal').classList.remove('open');
}

function formatFecha(f) {
    if (!f) return '';
    const d = new Date(f.replace(' ', 'T'));
    return d.toLocaleDateString('es-ES', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'});
}
</script>


