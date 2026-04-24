<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora');
            $table->unsignedBigInteger('usuarioId')->nullable();
            $table->decimal('total', 6, 2)->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'completada', 'cancelada'])->default('pendiente');
            $table->timestamps();

            $table->foreign('usuarioId')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
