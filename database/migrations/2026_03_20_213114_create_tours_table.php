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
        Schema::create('tours', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->text('descripcion')->nullable();
    $table->decimal('precio_total', 10, 2);
    $table->decimal('anticipo', 10, 2);
    $table->integer('cupos_totales');
    $table->integer('cupos_disponibles');
    $table->date('fecha_inicio')->nullable();
    $table->date('fecha_fin')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
