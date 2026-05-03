<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';

    public $timestamps = false;

    protected $fillable = [
        'localizador',
        'telefono_contacto',
        'franja_horaria',
        'cliente_id',
        'tecnico_id',
        'especialidad_id',
        'descripcion',
        'direccion',
        'fecha_servicio',
        'tipo_urgencia',
        'estado',
        'gestora_id',
        'precio_final',
        'comision_gestora',
        'zona_id',
    ];

    protected $casts = [
        'fecha_servicio' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class);
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function gestora()
    {
        return $this->belongsTo(Gestora::class);
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }
}
