<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';

    public $timestamps = false;

    protected $fillable = [
        'nombre_especialidad',
        'precio',
    ];

    public function tecnicos()
    {
        return $this->hasMany(Tecnico::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}
