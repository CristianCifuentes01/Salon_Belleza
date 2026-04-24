<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    /**
     * La tabla asociada con el modelo.
     */
    protected $table = 'servicios';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
        'duracion',
        'activo',
    ];

    /**
     * Los atributos que deben ser casteados.
     */
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    /**
     * Scope para filtrar servicios activos.
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', 1);
    }

    /**
     * Relación: Un servicio pertenece a muchas citas.
     */
    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'citas_servicios', 'servicioId', 'citaId');
    }

    /**
     * Obtener el precio formateado.
     */
    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio, 2);
    }

    /**
     * Obtener la duración formateada en horas y minutos.
     */
    public function getDuracionFormateadaAttribute(): string
    {
        if ($this->duracion >= 60) {
            $horas = floor($this->duracion / 60);
            $minutos = $this->duracion % 60;
            return $minutos > 0 ? "{$horas}h {$minutos}min" : "{$horas}h";
        }
        return "{$this->duracion} min";
    }
}
