<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'nombre' => 'Admin',
            'apellido' => 'AppSalon',
            'email' => 'admin@appsalon.com',
            'password' => Hash::make('password'),
            'telefono' => '3001234567',
            'admin' => 1,
            'confirmado' => 1,
            'token' => '',
        ]);

        // Crear usuario cliente de prueba
        User::create([
            'nombre' => 'María',
            'apellido' => 'García',
            'email' => 'cliente@appsalon.com',
            'password' => Hash::make('password'),
            'telefono' => '3009876543',
            'admin' => 0,
            'confirmado' => 1,
            'token' => '',
        ]);

        // Seed de servicios
        $this->call(ServicioSeeder::class);
    }
}
