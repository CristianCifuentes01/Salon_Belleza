<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'admin',
        'confirmado',
        'token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'admin' => 'boolean',
            'confirmado' => 'boolean',
        ];
    }

    /**
     * Verificar si el usuario es administrador.
     */
    public function isAdmin(): bool
    {
        return $this->admin === true;
    }

    /**
     * Obtener el nombre completo del usuario.
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Relación: Un usuario tiene muchas citas.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class, 'usuarioId');
    }
}
