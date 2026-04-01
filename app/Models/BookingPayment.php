<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_number',
        'reference',
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
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
