<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    protected $table = 'tecnicos';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'nombre_completo',
        'especialidad_id',
        'disponible',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}
