<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salon_horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('dia_semana')->unique();
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        $horarios = [];
        for ($dia = 1; $dia <= 7; $dia++) {
            $horarios[] = [
                'dia_semana' => $dia,
                'hora_apertura' => $dia === 7 ? null : '09:00:00',
                'hora_cierre' => $dia === 7 ? null : '18:00:00',
                'activo' => $dia !== 7,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('salon_horarios')->insert($horarios);
    }

    public function down(): void
    {
        Schema::dropIfExists('salon_horarios');
    }
};
