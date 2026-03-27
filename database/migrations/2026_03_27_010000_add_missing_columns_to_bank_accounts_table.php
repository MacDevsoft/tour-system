<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bank_accounts')) {
            return;
        }

        if (!Schema::hasColumn('bank_accounts', 'bank_name')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->string('bank_name')->nullable();
            });
        }

        if (!Schema::hasColumn('bank_accounts', 'account_number')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->string('account_number')->nullable();
            });
        }

        if (!Schema::hasColumn('bank_accounts', 'account_holder')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->string('account_holder')->nullable();
            });
        }

        if (!Schema::hasColumn('bank_accounts', 'account_type')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->string('account_type')->default('TRANSFERENCIA ESPEI');
            });
        }

        if (!Schema::hasColumn('bank_accounts', 'is_active')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                $table->boolean('is_active')->default(false);
            });
        }
    }

    public function down(): void
    {
        // No eliminamos columnas en rollback para evitar pérdida de datos.
    }
};
