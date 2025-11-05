<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bed extends Model
{
    use HasFactory;
    protected $fillable = [
        'ward_id',
        'bed_number',
        'bed_type',
        'status',
        'patient_id',
        'admission_date',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'datetime',
        ];
    }

    /**
     * Get the ward that owns the bed.
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Get the patient assigned to the bed.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the patient admissions for the bed.
     */
    public function patientAdmissions(): HasMany
    {
        return $this->hasMany(PatientAdmission::class);
    }
}
