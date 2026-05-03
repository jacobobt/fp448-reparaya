<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunidad extends Model
{
    protected $table = 'comunidades';

    protected $fillable = [
        'gestora_id',
        'zona_id',
        'nombre',
        'direccion',
    ];

    public function gestora()
    {
        return $this->belongsTo(Gestora::class);
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class);
    }
}
