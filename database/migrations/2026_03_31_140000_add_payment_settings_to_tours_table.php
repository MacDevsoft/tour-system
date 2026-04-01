<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 'payment_installments')) {
                $table->unsignedInteger('payment_installments')->nullable()->after('anticipo');
            }

            if (!Schema::hasColumn('tours', 'payment_deadline')) {
                $table->date('payment_deadline')->nullable()->after('fecha_fin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'payment_deadline')) {
                $table->dropColumn('payment_deadline');
            }

            if (Schema::hasColumn('tours', 'payment_installments')) {
                $table->dropColumn('payment_installments');
            }
        });
    }
};
