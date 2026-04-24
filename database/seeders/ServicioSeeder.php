<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Seed the servicios table with realistic salon services.
     */
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Corte de Cabello',
                'precio' => 25.00,
                'descripcion' => 'Corte profesional de cabello para damas y caballeros, incluye lavado y secado.',
                'duracion' => 45,
                'activo' => 1,
            ],
            [
                'nombre' => 'Tinte Completo',
                'precio' => 80.00,
                'descripcion' => 'Aplicación completa de tinte con productos de alta calidad. Incluye lavado y secado.',
                'duracion' => 120,
                'activo' => 1,
            ],
            [
                'nombre' => 'Manicure',
                'precio' => 20.00,
                'descripcion' => 'Tratamiento completo de uñas para manos, incluye limado, cutículas y esmaltado.',
                'duracion' => 30,
                'activo' => 1,
            ],
            [
                'nombre' => 'Pedicure',
                'precio' => 25.00,
                'descripcion' => 'Tratamiento completo de uñas para pies, incluye exfoliación y esmaltado.',
                'duracion' => 45,
                'activo' => 1,
            ],
            [
                'nombre' => 'Tratamiento Capilar',
                'precio' => 50.00,
                'descripcion' => 'Tratamiento de hidratación profunda para cabello dañado o seco.',
                'duracion' => 60,
                'activo' => 1,
            ],
            [
                'nombre' => 'Alisado Profesional',
                'precio' => 100.00,
                'descripcion' => 'Alisado con keratina para un cabello liso y brillante durante semanas.',
                'duracion' => 180,
                'activo' => 1,
            ],
            [
                'nombre' => 'Maquillaje Profesional',
                'precio' => 60.00,
                'descripcion' => 'Maquillaje profesional para eventos especiales, bodas o sesiones fotográficas.',
                'duracion' => 60,
                'activo' => 1,
            ],
            [
                'nombre' => 'Mechas / Highlights',
                'precio' => 90.00,
                'descripcion' => 'Aplicación de mechas o reflejos para darle dimensión y brillo al cabello.',
                'duracion' => 150,
                'activo' => 1,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
