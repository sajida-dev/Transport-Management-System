<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KycVerification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'verifiable_type',
        'verifiable_id',
        'document_type',
        'document_number',
        'document_file_path',
        'document_file_name',
        'document_file_type',
        'document_file_size',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'document_issue_date',
        'document_expiry_date',
        'notes'
    ];

    protected $casts = [
        'document_issue_date' => 'date',
        'document_expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function verifiable()
    {
        return $this->morphTo();
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'under_review' => 'badge-info',
            default => 'badge-secondary'
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isExpired()
    {
        return $this->document_expiry_date && $this->document_expiry_date->isPast();
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->document_expiry_date &&
            $this->document_expiry_date->isFuture() &&
            $this->document_expiry_date->diffInDays(now()) <= $days;
    }
}
