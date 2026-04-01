<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Tour extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_total',
        'anticipo',
        'payment_installments',
        'fecha_inicio',
        'fecha_fin',
        'payment_deadline',
        'ubicacion',
        'punto_encuentro',
        'hora_salida',
        'transporte',
        'capacidad',
        'cupos_totales',
        'cupos_disponibles',
        'is_enabled',
    ];

    protected $casts = [
        'payment_deadline' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function resolvedPaymentDeadline(): ?Carbon
    {
        if ($this->payment_deadline) {
            return Carbon::parse($this->payment_deadline)->startOfDay();
        }

        if ($this->fecha_inicio) {
            return Carbon::parse($this->fecha_inicio)->subDays(15)->startOfDay();
        }

        return null;
    }
}

