<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRole extends Pivot
{
    use SoftDeletes;

    protected $table = 'role_permissions';
    protected $fillable = [
        'role_id',
        'permission_id',
    ];
}
