<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class Booking extends Model
{
    protected static ?bool $supportsDigitalReceiptColumns = null;

    protected $fillable = [
        'user_id',
        'tour_id',
        'passenger_name',
        'purchase_id',
        'digital_receipt_code',
        'digital_receipt_generated_at',
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
        'digital_receipt_generated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (Booking $booking) {
            $booking->ensureDigitalReceiptMetadata();
        });
    }

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
        if (!$this->tour) {
            return null;
        }

        if (!empty($this->tour->payment_deadline)) {
            return Carbon::parse($this->tour->payment_deadline)->startOfDay();
        }

        if (!empty($this->tour->fecha_inicio)) {
            return Carbon::parse($this->tour->fecha_inicio)->subDays(15)->startOfDay();
        }

        return null;
    }

    public function ensureDigitalReceiptMetadata(): void
    {
        if (!$this->supportsDigitalReceiptMetadata()) {
            return;
        }

        if ($this->digital_receipt_code) {
            return;
        }

        $this->forceFill([
            'digital_receipt_code' => 'CDR-RES-' . $this->id . '-' . strtoupper(substr(md5((string) $this->purchase_id), 0, 6)),
            'digital_receipt_generated_at' => $this->digital_receipt_generated_at ?? now(),
        ])->saveQuietly();
    }

    protected function supportsDigitalReceiptMetadata(): bool
    {
        if (static::$supportsDigitalReceiptColumns !== null) {
            return static::$supportsDigitalReceiptColumns;
        }

        static::$supportsDigitalReceiptColumns = Schema::hasColumns('bookings', [
            'digital_receipt_code',
            'digital_receipt_generated_at',
        ]);

        return static::$supportsDigitalReceiptColumns;
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
        $requestedInstallments = max(0, (int) ($this->tour->payment_installments ?? 0));
        $paymentDates = $this->calculatePaymentDates($createdAt, $deadline, $requestedInstallments > 0 ? $requestedInstallments : null);
        $installments = max(1, count($paymentDates));

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

    protected function calculatePaymentDates(Carbon $from, Carbon $deadline, ?int $requestedInstallments = null): array
    {
        if ($deadline->lte($from)) {
            return [$deadline->copy()];
        }

        $dates = $this->collectQuincenaDates($from, $deadline);

        if (empty($dates)) {
            $dates = [$deadline->copy()];
        }

        if (!$requestedInstallments || $requestedInstallments <= 0) {
            return $dates;
        }

        if ($requestedInstallments === 1) {
            return [$deadline->copy()];
        }

        if (count($dates) >= $requestedInstallments) {
            return $this->spreadDatesAcrossPeriod($dates, $requestedInstallments);
        }

        $generated = [];
        $totalDays = max(1, $from->diffInDays($deadline));

        for ($index = 1; $index <= $requestedInstallments; $index++) {
            $ratio = $index / $requestedInstallments;
            $candidate = $from->copy()->addDays((int) round($totalDays * $ratio))->startOfDay();

            if ($index === $requestedInstallments || $candidate->gt($deadline)) {
                $candidate = $deadline->copy();
            }

            $generated[] = $candidate;
        }

        return $generated;
    }

    protected function collectQuincenaDates(Carbon $from, Carbon $deadline): array
    {
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

        usort($dates, fn (Carbon $a, Carbon $b) => $a->timestamp <=> $b->timestamp);

        return $dates;
    }

    protected function spreadDatesAcrossPeriod(array $dates, int $count): array
    {
        if ($count >= count($dates)) {
            return $dates;
        }

        $selected = [];
        $lastIndex = -1;
        $maxIndex = count($dates) - 1;

        for ($position = 0; $position < $count; $position++) {
            $index = (int) round($position * $maxIndex / max(1, $count - 1));
            $index = max($index, $lastIndex + 1);
            $index = min($index, $maxIndex - (($count - 1) - $position));

            $selected[] = $dates[$index]->copy();
            $lastIndex = $index;
        }

        return $selected;
    }
}
