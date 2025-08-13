<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'load_id',
        'transporter_id',
        'truck_id',
        'driver_id',
        'quoted_amount',
        'accepted_amount',
        'currency',
        'notes',
        'special_instructions',
        'status',
        'payment_status',
        'quoted_at',
        'accepted_at',
        'rejected_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'cancelled_by',
        'actual_distance_km',
        'fuel_consumption_liters',
        'delivery_time_hours',
        'rating',
        'feedback'
    ];

    protected $casts = [
        'quoted_amount' => 'decimal:2',
        'accepted_amount' => 'decimal:2',
        'actual_distance_km' => 'decimal:2',
        'fuel_consumption_liters' => 'decimal:2',
        'rating' => 'decimal:2',
        'quoted_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function loadData()
    {
        return $this->belongsTo(Load::class);
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'accepted' => 'badge-info',
            'rejected' => 'badge-danger',
            'in_progress' => 'badge-primary',
            'completed' => 'badge-success',
            'cancelled' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }

    public function getPaymentStatusBadgeClassAttribute()
    {
        return match ($this->payment_status) {
            'pending' => 'badge-warning',
            'partial' => 'badge-info',
            'paid' => 'badge-success',
            'overdue' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    // generate booking number
    public static function generateBookingNumber()
    {
        $bookingNumber = rand(1000, 9999);
        $bookingNumberExists = Booking::where('booking_number', $bookingNumber)->exists();
        if ($bookingNumberExists) {
            return self::generateBookingNumber();
        } else {
            return $bookingNumber;
        }
    }
}
