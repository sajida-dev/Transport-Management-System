<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transporter_id',
        'driver_id',
        'registration_number',
        'make',
        'model',
        'year',
        'color',
        'type',
        'capacity_tonnes',
        'length_meters',
        'width_meters',
        'height_meters',
        'engine_number',
        'chassis_number',
        'insurance_policy_number',
        'insurance_expiry',
        'fitness_certificate_number',
        'fitness_expiry',
        'permit_number',
        'permit_expiry',
        'status',
        'notes',
        'photo',
        'documents',
        'tracking_data'
    ];

    protected $casts = [
        'insurance_expiry' => 'date',
        'fitness_expiry' => 'date',
        'permit_expiry' => 'date',
        'documents' => 'array',
        'tracking_data' => 'array',
    ];

    public function loads()
    {
        return $this->hasMany(Load::class);
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }


    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'maintenance' => 'badge-warning',
            'out_of_service' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')->whereNull('driver_id');
    }

    public function isExpired()
    {
        return ($this->insurance_expiry && $this->insurance_expiry->isPast()) ||
            ($this->fitness_expiry && $this->fitness_expiry->isPast()) ||
            ($this->permit_expiry && $this->permit_expiry->isPast());
    }
}
