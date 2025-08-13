<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'transporter_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'license_number',
        'license_type',
        'license_expiry_date',
        'date_of_birth',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'medical_certificate_number',
        'medical_certificate_expiry',
        'experience_years',
        'vehicle_types',
        'status',
        'notes',
        'profile_photo',
        'license_photo',
        'medical_certificate_photo',
        'kyc_documents'
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
        'date_of_birth' => 'date',
        'medical_certificate_expiry' => 'date',
        'vehicle_types' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'driver_id');
    }


    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function kycVerifications()
    {
        return $this->morphMany(KycVerification::class, 'verifiable');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-yellow-100 text-yellow-800',
            'expired' => 'bg-red-100 text-red-800',
            'pending_verification' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-200 text-gray-600',
        };
    }

    public function isLicenseExpired()
    {
        return $this->license_expiry_date && $this->license_expiry_date->isPast();
    }

    public function isMedicalCertificateExpired()
    {
        return $this->medical_certificate_expiry && $this->medical_certificate_expiry->isPast();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }
}
