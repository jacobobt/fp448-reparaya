<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidaciones';

    protected $fillable = [
        'gestora_id',
        'mes',
        'total_servicios',
        'total_comision',
    ];

    public function gestora()
    {
        return $this->belongsTo(Gestora::class);
    }
}
