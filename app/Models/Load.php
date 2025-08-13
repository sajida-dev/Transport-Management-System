<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Load extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'load_owner_id',
        'load_number',
        'title',
        'description',
        'load_type',
        'weight_tonnes',
        'length_meters',
        'width_meters',
        'height_meters',
        'pickup_location',
        'pickup_address',
        'pickup_city',
        'pickup_state',
        'pickup_postal_code',
        'pickup_country',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_date',
        'pickup_time',
        'pickup_contact_name',
        'pickup_contact_phone',
        'pickup_instructions',
        'delivery_location',
        'delivery_address',
        'delivery_city',
        'delivery_state',
        'delivery_postal_code',
        'delivery_country',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_date',
        'delivery_time',
        'delivery_contact_name',
        'delivery_contact_phone',
        'delivery_instructions',
        'rate_per_km',
        'total_distance_km',
        'total_amount',
        'currency',
        'required_vehicle_types',
        'special_requirements',
        'requires_refrigeration',
        'requires_special_equipment',
        'is_hazardous',
        'status',
        'priority',
        'notes',
        'documents'
    ];

    protected $casts = [
        'weight_tonnes' => 'decimal:2',
        'length_meters' => 'decimal:2',
        'width_meters' => 'decimal:2',
        'height_meters' => 'decimal:2',
        'pickup_latitude' => 'decimal:8',
        'pickup_longitude' => 'decimal:8',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'pickup_date' => 'datetime',
        'delivery_date' => 'datetime',
        'rate_per_km' => 'decimal:2',
        'total_distance_km' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'required_vehicle_types' => 'array',
        'special_requirements' => 'array',
        'requires_refrigeration' => 'boolean',
        'requires_special_equipment' => 'boolean',
        'is_hazardous' => 'boolean',
        'documents' => 'array',
    ];

    public function loadOwner()
    {
        return $this->belongsTo(LoadOwner::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(Assignment::class)->latestOfMany();
    }


    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'assigned' => 'badge-info',
            'in_transit' => 'badge-primary',
            'delivered' => 'badge-success',
            'cancelled' => 'badge-danger',
            'completed' => 'badge-success',
            default => 'badge-secondary'
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match ($this->priority) {
            'low' => 'badge-secondary',
            'medium' => 'badge-info',
            'high' => 'badge-warning',
            'urgent' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
}
