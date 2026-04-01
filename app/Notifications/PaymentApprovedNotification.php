<?php

namespace App\Notifications;

use App\Models\BookingPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly BookingPayment $payment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $booking = $this->payment->booking;
        $tour = $booking?->tour;

        return [
            'title' => 'Tu pago fue aceptado',
            'message' => 'Se aprobó tu comprobante y tu avance se actualizó correctamente.',
            'payment_reference' => $this->payment->reference,
            'amount' => (float) $this->payment->amount,
            'tour_name' => $tour?->nombre,
            'approved_at' => optional($this->payment->approved_at)->format('d/m/Y H:i'),
            'url' => $booking ? route('bookings.my-tours', ['tour_id' => $booking->tour_id, 'booking_id' => $booking->id]) : route('bookings.my-tours'),
        ];
    }
}
