<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transporter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'registration_number',
        'tax_id',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'operating_license_number',
        'operating_license_expiry',
        'insurance_policy_number',
        'insurance_expiry',
        'status',
        'notes',
        'logo',
        'documents'
    ];

    protected $casts = [
        'operating_license_expiry' => 'date',
        'insurance_expiry' => 'date',
        'documents' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function kycVerifications()
    {
        return $this->morphMany(KycVerification::class, 'verifiable');
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'suspended' => 'badge-warning',
            'pending_verification' => 'badge-info',
            default => 'badge-secondary'
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePendingVerification($query)
    {
        return $query->where('status', 'pending_verification');
    }
}
