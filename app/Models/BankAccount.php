<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'account_type',
        'is_active',
    ];

    public static function active()
    {
        return static::where('is_active', true)->first();
    }
}
