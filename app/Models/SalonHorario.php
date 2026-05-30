<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalonHorario extends Model
{
    protected $table = 'salon_horarios';

    protected $fillable = [
        'dia_semana',
        'hora_apertura',
        'hora_cierre',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}
