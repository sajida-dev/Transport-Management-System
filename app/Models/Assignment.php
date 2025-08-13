<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Assignment extends Pivot
{
    protected $fillable = [
        'load_id',
        'driver_id',
        'truck_id',
        'status',
        'assigned_at',
        'delivered_at',
        'cancel_reason',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function loadModel()
    {
        return $this->belongsTo(Load::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
