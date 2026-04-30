@extends('layouts.app')

@section('title', 'Nuevo aviso - ReparaYa')

@section('content')
    <h1>Nuevo aviso</h1>

    <div class="actions">
        <a class="button" href="{{ route('incidencias.index') }}">Mis avisos</a>
    </div>

    @if ($errors->any())
        <ul class="error">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('incidencias.store') }}">
        @csrf

        <label>Teléfono de contacto</label><br>
        <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto', auth()->user()->telefono) }}" required><br><br>

        <label>Especialidad</label><br>
        <select name="especialidad_id" required>
            <option value="">Selecciona una especialidad</option>
            @foreach ($especialidades as $especialidad)
                <option value="{{ $especialidad->id }}" @selected(old('especialidad_id') == $especialidad->id)>
                    {{ $especialidad->nombre_especialidad }} - {{ number_format($especialidad->precio, 2) }} €
                </option>
            @endforeach
        </select><br><br>

        <label>Descripción</label><br>
        <textarea name="descripcion" required>{{ old('descripcion') }}</textarea><br><br>

        <label>Dirección</label><br>
        <input type="text" name="direccion" value="{{ old('direccion') }}" required><br><br>

        <label>Fecha del servicio</label><br>
        <input type="date" name="fecha_servicio" value="{{ old('fecha_servicio') }}" required><br><br>

        <label>Franja horaria</label><br>
        <select name="franja_horaria" required>
            <option value="09:00-13:00" @selected(old('franja_horaria') === '09:00-13:00')>09:00-13:00</option>
            <option value="16:00-20:00" @selected(old('franja_horaria') === '16:00-20:00')>16:00-20:00</option>
        </select><br><br>

        <label>Tipo de urgencia</label><br>
        <select name="tipo_urgencia" required>
            <option value="Estandar" @selected(old('tipo_urgencia') === 'Estandar')>Estándar</option>
            <option value="Urgente" @selected(old('tipo_urgencia') === 'Urgente')>Urgente</option>
        </select><br><br>

        <button type="submit">Crear aviso</button>
    </form>
@endsection
