<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'specialization',
        'department',
        'provider',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // This only hashes when SETTING, not when GETTING
        ];
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->fname . ' ' . $this->mname . ' ' . $this->lname);
    }

    /**
     * Get appointments where user is a patient.
     */
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Get appointments where user is a doctor.
     */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Get appointments where user is a nurse.
     */
    public function nurseAppointments()
    {
        return $this->hasMany(Appointment::class, 'nurse_id');
    }

    /**
     * Get patient records.
     */
    public function patientRecord()
    {
        return $this->hasOne(PatientRecord::class);
    }

    /**
     * Get doctor schedules.
     */
    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    /**
     * Get patient admissions.
     */
    public function patientAdmissions()
    {
        return $this->hasMany(PatientAdmission::class, 'patient_id');
    }

    /**
     * Get beds assigned to patient.
     */
    public function assignedBeds()
    {
        return $this->hasMany(Bed::class, 'patient_id');
    }
}
