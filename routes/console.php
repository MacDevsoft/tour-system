<?php

use App\Models\Booking;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('bookings:cancel-overdue-payments', function () {
    $cancelled = 0;

    Booking::with(['tour', 'payments'])
        ->whereIn('status', ['pending', 'approved'])
        ->chunkById(100, function ($bookings) use (&$cancelled) {
            foreach ($bookings as $booking) {
                $previousStatus = $booking->status;

                $booking->ensurePaymentSchedule();
                $booking->refreshPaymentPlanStatus();

                if ($previousStatus !== 'rejected' && $booking->fresh()->status === 'rejected') {
                    $cancelled++;
                }
            }
        });

    $this->info("Reservas canceladas por mora: {$cancelled}");
})->purpose('Cancela reservas con pagos vencidos después de la tolerancia');

Schedule::command('bookings:cancel-overdue-payments')->hourly();
