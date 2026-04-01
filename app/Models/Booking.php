<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'tour_id',
        'passenger_name',
        'purchase_id',
        'amount_paid',
        'receipt_path',
        'status',
        'approved_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class)->orderBy('payment_number');
    }

    public function paymentDeadline(): ?Carbon
    {
        if (!$this->tour || empty($this->tour->fecha_inicio)) {
            return null;
        }

        return Carbon::parse($this->tour->fecha_inicio)->subDays(15)->startOfDay();
    }

    public function totalApprovedPayments(): float
    {
        $approvedInstallments = (float) $this->payments()
            ->where('status', 'approved')
            ->sum('amount');

        return round(((float) $this->amount_paid) + $approvedInstallments, 2);
    }

    public function remainingAmount(): float
    {
        $total = (float) ($this->tour->precio_total ?? 0);

        return round(max(0, $total - $this->totalApprovedPayments()), 2);
    }

    public function nextPendingPayment(): ?BookingPayment
    {
        $this->loadMissing('payments');

        return $this->payments->first(function (BookingPayment $payment) {
            return in_array($payment->status, ['pending', 'late', 'submitted'], true);
        });
    }

    public function ensurePaymentSchedule(): void
    {
        $this->loadMissing('tour', 'payments');

        if (!$this->tour || $this->payments->isNotEmpty()) {
            return;
        }

        $remaining = round(max(0, (float) $this->tour->precio_total - (float) $this->amount_paid), 2);
        if ($remaining <= 0) {
            return;
        }

        $createdAt = ($this->created_at ? $this->created_at->copy() : now())->startOfDay();
        $deadline = $this->paymentDeadline() ?? $createdAt->copy()->addDays(15);
        $paymentDates = $this->calculatePaymentDates($createdAt, $deadline);
        $installments = count($paymentDates);

        $baseAmount = floor(($remaining / $installments) * 100) / 100;
        $accumulated = 0;

        foreach ($paymentDates as $index => $dueDate) {
            $number = $index + 1;
            $amount = $number === $installments
                ? round($remaining - $accumulated, 2)
                : round($baseAmount, 2);

            $accumulated += $amount;

            $this->payments()->create([
                'payment_number' => $number,
                'reference' => 'ABN-' . $this->id . '-' . str_pad((string) $number, 2, '0', STR_PAD_LEFT),
                'amount' => $amount,
                'due_date' => $dueDate->toDateString(),
                'grace_until' => $dueDate->copy()->addDays(3)->toDateString(),
                'status' => 'pending',
            ]);
        }

        $this->unsetRelation('payments');
        $this->load('payments');
    }

    public function refreshPaymentPlanStatus(): void
    {
        $this->loadMissing('payments', 'tour');

        if ($this->payments->isEmpty()) {
            return;
        }

        $today = now()->startOfDay();
        $mustCancel = false;

        foreach ($this->payments as $payment) {
            if (in_array($payment->status, ['approved', 'submitted', 'cancelled'], true)) {
                continue;
            }

            $dueDate = Carbon::parse($payment->due_date)->startOfDay();
            $graceUntil = Carbon::parse($payment->grace_until)->startOfDay();

            if ($today->gt($graceUntil)) {
                $payment->update(['status' => 'cancelled']);
                $payment->status = 'cancelled';
                $mustCancel = true;
                continue;
            }

            if ($today->gt($dueDate) && $payment->status === 'pending') {
                $payment->update(['status' => 'late']);
                $payment->status = 'late';
            }
        }

        if ($mustCancel && $this->status !== 'rejected') {
            $this->update([
                'status' => 'rejected',
                'cancelled_at' => now(),
                'cancellation_reason' => 'La reserva fue cancelada por no cubrir un pago dentro de los 3 días posteriores a la fecha límite.',
            ]);

            $this->status = 'rejected';
            $this->cancelled_at = now();
            $this->cancellation_reason = 'La reserva fue cancelada por no cubrir un pago dentro de los 3 días posteriores a la fecha límite.';
        }
    }

    protected function calculatePaymentDates(Carbon $from, Carbon $deadline): array
    {
        if ($deadline->lte($from)) {
            return [$from->copy()];
        }

        $dates = [];
        $seen = [];
        $cursor = $from->copy()->startOfMonth();

        while ($cursor->lte($deadline)) {
            foreach ([1, 15] as $day) {
                $candidate = $cursor->copy()->day($day)->startOfDay();

                if ($candidate->gt($from) && $candidate->lte($deadline)) {
                    $key = $candidate->toDateString();
                    if (!isset($seen[$key])) {
                        $seen[$key] = true;
                        $dates[] = $candidate;
                    }
                }
            }

            $cursor->addMonthNoOverflow()->startOfMonth();
        }

        if (empty($dates)) {
            $dates[] = $deadline->copy();
        }

        usort($dates, fn (Carbon $a, Carbon $b) => $a->timestamp <=> $b->timestamp);

        return $dates;
    }
}
