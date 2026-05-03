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
        'gestora_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    public function tecnico()
    {
        return $this->hasOne(Tecnico::class, 'usuario_id');
    }

    public function gestora()
    {
        return $this->belongsTo(Gestora::class);
    }
}
