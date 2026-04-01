<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BookingPayment extends Model
{
    protected static ?bool $supportsDigitalReceiptColumns = null;

    protected $fillable = [
        'booking_id',
        'payment_number',
        'reference',
        'digital_receipt_code',
        'digital_receipt_generated_at',
        'amount',
        'due_date',
        'grace_until',
        'status',
        'receipt_path',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'grace_until' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'digital_receipt_generated_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (BookingPayment $payment) {
            $payment->ensureDigitalReceiptMetadata();
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
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
            'digital_receipt_code' => 'CDR-PAY-' . $this->id . '-' . strtoupper(substr(md5((string) $this->reference), 0, 6)),
            'digital_receipt_generated_at' => $this->digital_receipt_generated_at ?? now(),
        ])->saveQuietly();
    }

    protected function supportsDigitalReceiptMetadata(): bool
    {
        if (static::$supportsDigitalReceiptColumns !== null) {
            return static::$supportsDigitalReceiptColumns;
        }

        static::$supportsDigitalReceiptColumns = Schema::hasColumns('booking_payments', [
            'digital_receipt_code',
            'digital_receipt_generated_at',
        ]);

        return static::$supportsDigitalReceiptColumns;
    }
}
