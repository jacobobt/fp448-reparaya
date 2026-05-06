@extends('layouts.app')

@section('title', 'Mi agenda - ReparaYa')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
<style>
    .fc-event-urgente { background-color: #dc2626 !important; border-color: #b91c1c !important; color:white !important; }
    .fc-event-estandar { background-color: #0f766e !important; border-color: #0d6460 !important; color:white !important; }
    .fc .fc-toolbar-title { font-size: 1.1rem; }
    .fc .fc-button { background: #0f766e; border-color: #0d6460; }
    .fc .fc-button:hover { background: #0d6460; border-color: #0b5a56; }
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active { background: #064e3b; border-color: #064e3b; }
    .legend { display: flex; gap: 20px; margin: 12px 0; font-size: 14px; align-items: center; }
    .legend-dot { width: 14px; height: 14px; border-radius: 3px; display: inline-block; margin-right: 5px; }
    .toggle-btn { background: #e6fffa; color: #0f766e; border: 1px solid #0f766e; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 13px; }
    .toggle-btn.active { background: #0f766e; color: white; }
    #calendar-wrap { background: white; padding: 20px; border-radius: 8px; border: 1px solid #d9e2ec; margin-top: 16px; }
    #table-wrap { margin-top: 20px; }
    #event-modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center; }
    #event-modal.open { display:flex; }
    .modal-box { background:white; border-radius:10px; padding:28px; max-width:420px; width:90%; position:relative; }
    .modal-box h3 { margin:0 0 14px; color:#0f766e; }
    .modal-close { position:absolute; top:12px; right:16px; font-size:20px; cursor:pointer; color:#666; background:none; border:none; }
    .modal-row { display:flex; gap:8px; margin-bottom:8px; font-size:14px; }
    .modal-label { font-weight:bold; min-width:110px; color:#555; }
    .badge-urgente { background:#fef2f2; color:#dc2626; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:bold; }
    .badge-estandar { background:#e6fffa; color:#0f766e; padding:2px 10px; border-radius:20px; font-size:12px; font-weight:bold; }
</style>
@endpush

@section('content')
<h1>Mi agenda</h1>
<p>Técnico: <strong>{{ $tecnico->nombre_completo }}</strong></p>

@if (session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
    <div class="legend">
        <span><span class="legend-dot" style="background:#dc2626"></span> Urgente</span>
        <span><span class="legend-dot" style="background:#0f766e"></span> Estándar</span>
    </div>
    <div style="display:flex; gap:8px;">
        <button class="toggle-btn active" id="btn-calendario" onclick="mostrarVista('calendario')">Calendario</button>
        <button class="toggle-btn" id="btn-lista" onclick="mostrarVista('lista')">Lista</button>
    </div>
</div>

<div id="calendar-wrap">
    <div id="calendar"></div>
</div>

<div id="table-wrap" style="display:none;">
    <table>
        <thead>
        <tr>
            <th>Localizador</th><th>Cliente</th><th>Teléfono</th><th>Especialidad</th>
            <th>Dirección</th><th>Fecha</th><th>Franja</th><th>Urgencia</th><th>Estado</th><th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($incidencias as $inc)
            <tr>
                <td>{{ $inc->localizador }}</td>
                <td>{{ $inc->cliente->nombre ?? '-' }}</td>
                <td>{{ $inc->telefono_contacto }}</td>
                <td>{{ $inc->especialidad->nombre_especialidad ?? '-' }}</td>
                <td>{{ $inc->direccion }}</td>
                <td>{{ $inc->fecha_servicio->format('d/m/Y H:i') }}</td>
                <td>{{ $inc->franja_horaria }}</td>
                <td>
                    @if($inc->tipo_urgencia === 'Urgente')
                        <span class="badge-urgente">Urgente</span>
                    @else
                        <span class="badge-estandar">Estándar</span>
                    @endif
                </td>
                <td>{{ $inc->estado }}</td>
                <td>
                    @if ($inc->estado !== 'Finalizada')
                        <form method="POST" action="{{ route('tecnico.incidencias.finalizar', $inc) }}">
                            @csrf @method('PATCH')
                            <button type="submit">Finalizar</button>
                        </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="10">No tienes servicios asignados.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div id="event-modal">
    <div class="modal-box">
        <button class="modal-close" onclick="cerrarModal()">&#x2715;</button>
        <h3 id="m-localizador"></h3>
        <div class="modal-row"><span class="modal-label">Urgencia:</span><span id="m-urgencia"></span></div>
        <div class="modal-row"><span class="modal-label">Especialidad:</span><span id="m-especialidad"></span></div>
        <div class="modal-row"><span class="modal-label">Fecha:</span><span id="m-fecha"></span></div>
        <div class="modal-row"><span class="modal-label">Franja:</span><span id="m-franja"></span></div>
        <div class="modal-row"><span class="modal-label">Dirección:</span><span id="m-direccion"></span></div>
        <div class="modal-row"><span class="modal-label">Cliente:</span><span id="m-cliente"></span></div>
        <div class="modal-row"><span class="modal-label">Teléfono:</span><span id="m-telefono"></span></div>
        <div class="modal-row"><span class="modal-label">Estado:</span><span id="m-estado"></span></div>
        <div id="m-form" style="margin-top:14px;"></div>
    </div>
</div>

{{-- La URL de finalizar se genera por cada incidencia directamente en PHP --}}
@php
$eventosData = $incidencias->map(function($inc) {
    $urgencia     = $inc->tipo_urgencia ?? 'Estandar';
    $especialidad = $inc->especialidad->nombre_especialidad ?? 'Servicio';

    return [
        'id'        => $inc->id,
        'title'     => '[' . strtoupper(substr($urgencia, 0, 3)) . '] ' . $especialidad,
        'start'     => $inc->fecha_servicio->format('Y-m-d\TH:i:s'),
        'className' => $urgencia === 'Urgente' ? 'fc-event-urgente' : 'fc-event-estandar',
        'extendedProps' => [
            'localizador'   => $inc->localizador ?? '',
            'urgencia'      => $urgencia,
            'especialidad'  => $especialidad,
            'franja'        => $inc->franja_horaria ?? '',
            'direccion'     => $inc->direccion ?? '',
            'cliente'       => $inc->cliente->nombre ?? '-',
            'telefono'      => $inc->telefono_contacto ?? '',
            'estado'        => $inc->estado ?? '',
            // URL generada correctamente por Laravel para cada incidencia
            'finalizar_url' => route('tecnico.incidencias.finalizar', $inc->id),
        ],
    ];
})->values()->toArray();

$eventosJson = json_encode($eventosData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
@endphp

<script id="eventos-json" type="application/json">{!! $eventosJson !!}</script>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.global.min.js'></script>
<script>
    const eventos = JSON.parse(document.getElementById('eventos-json').textContent);
    const csrfToken = "{{ csrf_token() }}";

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        locale: 'es',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Dia' },
        events: eventos,
        eventClick: function(info) { abrirModal(info.event); },
        height: 'auto',
    });
    calendar.render();

    function mostrarVista(v) {
        document.getElementById('calendar-wrap').style.display = v === 'calendario' ? '' : 'none';
        document.getElementById('table-wrap').style.display    = v === 'lista'      ? '' : 'none';
        document.getElementById('btn-calendario').classList.toggle('active', v === 'calendario');
        document.getElementById('btn-lista').classList.toggle('active', v === 'lista');
    }

    function abrirModal(event) {
        const p = event.extendedProps;
        document.getElementById('m-localizador').textContent = p.localizador;
        document.getElementById('m-urgencia').innerHTML = p.urgencia === 'Urgente'
            ? '<span class="badge-urgente">Urgente</span>'
            : '<span class="badge-estandar">Estandar</span>';
        document.getElementById('m-especialidad').textContent = p.especialidad;
        document.getElementById('m-fecha').textContent = event.start
            ? event.start.toLocaleDateString('es-ES', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'})
            : '-';
        document.getElementById('m-franja').textContent    = p.franja;
        document.getElementById('m-direccion').textContent = p.direccion;
        document.getElementById('m-cliente').textContent   = p.cliente;
        document.getElementById('m-telefono').textContent  = p.telefono;
        document.getElementById('m-estado').textContent    = p.estado;

        const formDiv = document.getElementById('m-form');
        if (p.estado !== 'Finalizada') {
            formDiv.innerHTML =
                '<form method="POST" action="' + p.finalizar_url + '">' +
                '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                '<input type="hidden" name="_method" value="PATCH">' +
                '<button type="submit" style="width:100%">Marcar como finalizada</button>' +
                '</form>';
        } else {
            formDiv.innerHTML = '<p style="color:#047857;font-weight:bold">Servicio finalizado</p>';
        }

        document.getElementById('event-modal').classList.add('open');
    }

    function cerrarModal() {
        document.getElementById('event-modal').classList.remove('open');
    }

    document.getElementById('event-modal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });
</script>
@endpush
@endsection