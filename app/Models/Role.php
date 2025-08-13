<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that belong to this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(RoleUser::class)
            ->withTimestamps()
            ->withPivot(['deleted_at']);
    }

    /**
     * Get the permissions that belong to this role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->using(PermissionRole::class)
            ->withTimestamps();
    }

    /**
     * Check if the role has a specific permission
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }
        return $this->permissions->contains($permission);
    }

    /**
     * Check if the role has any of the given permissions
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = explode('|', $permissions);
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the role has all of the given permissions
     */
    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = explode('|', $permissions);
        }

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Scope to get only active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only inactive roles
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_active ? 'badge-success' : 'badge-danger';
    }

    /**
     * Get the status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }
}
