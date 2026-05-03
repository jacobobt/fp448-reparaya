<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gestora extends Model
{
    protected $table = 'gestoras';

    protected $fillable = [
        'nombre',
        'comision_porcentaje',
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }

    public function liquidaciones()
    {
        return $this->hasMany(Liquidacion::class);
    }
}
