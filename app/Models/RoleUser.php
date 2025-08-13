<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleUser extends Pivot
{
    use SoftDeletes;

    protected $table = 'role_user';

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that belongs to this role assignment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role that belongs to this user assignment
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
