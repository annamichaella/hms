<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'patient_name',
        'doctor_name',
        'service',
        'amount',
        'status',
        'billing_date',
        'due_date',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'billing_date' => 'date',
            'due_date' => 'date',
        ];
    }
}
