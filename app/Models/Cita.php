<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'citas';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'fecha',
        'hora',
        'usuarioId',
        'total',
        'estado',
    ];

    /**
     * Los atributos que deben ser casteados.
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Relación: Una cita pertenece a un usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarioId');
    }

    /**
     * Relación: Una cita tiene muchos servicios.
     */
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'citas_servicios', 'citaId', 'servicioId');
    }

    /**
     * Obtener el total formateado.
     */
    public function getTotalFormateadoAttribute(): string
    {
        return '$' . number_format($this->total, 2);
    }

    /**
     * Obtener el color del badge según el estado.
     */
    public function getEstadoColorAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => 'yellow',
            'confirmada' => 'blue',
            'completada' => 'green',
            'cancelada' => 'red',
            default => 'gray',
        };
    }

    /**
     * Scope: Citas de hoy.
     */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', now()->toDateString());
    }

    /**
     * Scope: Citas de esta semana.
     */
    public function scopeEstaSemana($query)
    {
        return $query->whereBetween('fecha', [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString(),
        ]);
    }

    /**
     * Scope: Citas por estado.
     */
    public function scopeEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }
}
