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
    Schema::table('tours', function (Blueprint $table) {
        $table->string('ubicacion')->nullable();
        $table->string('punto_encuentro')->nullable();
        $table->time('hora_salida')->nullable();
        $table->time('hora_regreso')->nullable();
        $table->string('transporte')->nullable();
        $table->integer('capacidad')->default(0);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
