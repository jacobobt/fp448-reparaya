<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'telefono',
    ];

    protected $hidden = [
        'password',
    ];

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    public function tecnico()
    {
        return $this->hasOne(Tecnico::class, 'usuario_id');
    }
}
