<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoadOwner extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'tax_id',
        'business_license_number',
        'business_license_expiry',
        'status',
        'notes',
        'logo',
        'documents'
    ];

    protected $casts = [
        'business_license_expiry' => 'date',
        'documents' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loads()
    {
        return $this->hasMany(Load::class);
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
        return match($this->status) {
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