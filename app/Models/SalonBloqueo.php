<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalonBloqueo extends Model
{
    protected $table = 'salon_bloqueos';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'motivo',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }
}
