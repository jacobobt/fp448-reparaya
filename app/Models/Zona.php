<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $table = 'zonas';

    protected $fillable = [
        'nombre',
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    public function comunidades()
    {
        return $this->hasMany(Comunidad::class);
    }
}
