<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'digital_receipt_code')) {
                $table->string('digital_receipt_code')->nullable()->unique()->after('purchase_id');
            }

            if (!Schema::hasColumn('bookings', 'digital_receipt_generated_at')) {
                $table->timestamp('digital_receipt_generated_at')->nullable()->after('cancellation_reason');
            }
        });

        Schema::table('booking_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('booking_payments', 'digital_receipt_code')) {
                $table->string('digital_receipt_code')->nullable()->unique()->after('reference');
            }

            if (!Schema::hasColumn('booking_payments', 'digital_receipt_generated_at')) {
                $table->timestamp('digital_receipt_generated_at')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_payments', function (Blueprint $table) {
            if (Schema::hasColumn('booking_payments', 'digital_receipt_generated_at')) {
                $table->dropColumn('digital_receipt_generated_at');
            }

            if (Schema::hasColumn('booking_payments', 'digital_receipt_code')) {
                $table->dropUnique(['digital_receipt_code']);
                $table->dropColumn('digital_receipt_code');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'digital_receipt_generated_at')) {
                $table->dropColumn('digital_receipt_generated_at');
            }

            if (Schema::hasColumn('bookings', 'digital_receipt_code')) {
                $table->dropUnique(['digital_receipt_code']);
                $table->dropColumn('digital_receipt_code');
            }
        });
    }
};
