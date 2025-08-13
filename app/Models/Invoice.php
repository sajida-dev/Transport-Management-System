<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'booking_id',
        'load_owner_id',
        'transporter_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'status',
        'paid_amount',
        'paid_date',
        'payment_method',
        'transaction_id',
        'fuel_surcharge',
        'waiting_charges',
        'detention_charges',
        'other_charges',
        'notes',
        'terms_conditions',
        'payment_instructions',
        'pdf_path',
        'sent_at',
        'viewed_at'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'fuel_surcharge' => 'decimal:2',
        'waiting_charges' => 'decimal:2',
        'detention_charges' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function loadOwner()
    {
        return $this->belongsTo(LoadOwner::class);
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'sent' => 'badge-info',
            'paid' => 'badge-success',
            'overdue' => 'badge-danger',
            'cancelled' => 'badge-warning',
            default => 'badge-secondary'
        };
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['draft', 'sent']);
    }

    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'paid';
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
} 