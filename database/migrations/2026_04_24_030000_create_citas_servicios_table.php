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
        Schema::create('citas_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('citaId');
            $table->unsignedBigInteger('servicioId');

            $table->foreign('citaId')
                  ->references('id')
                  ->on('citas')
                  ->onDelete('cascade');

            $table->foreign('servicioId')
                  ->references('id')
                  ->on('servicios')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas_servicios');
    }
};
