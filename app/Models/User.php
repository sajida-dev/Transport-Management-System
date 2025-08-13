<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'gender',
        'nrc',
        'address',
        'profile_image_url',
        'role',
        'is_active',
        'last_login_at',
        'email_verified_at',
        'password',
        'current_team_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->using(RoleUser::class)
            ->withTimestamps()
            ->withPivot(['deleted_at']);
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }
    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = explode('|', $roles);
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
    public function hasAllRoles($roles)
    {
        if (is_string($roles)) {
            $roles = explode('|', $roles);
        }

        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    public function getFullNameAttribute()
    {
        $firstName = $this->first_name ?: '';
        $lastName = $this->last_name ?: '';
        $fullName = trim($firstName . ' ' . $lastName);
        return $fullName ?: $this->name;
    }
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_active ? 'badge-success' : 'badge-danger';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('roles', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    public function isOnline()
    {
        return $this->last_login_at && $this->last_login_at->diffInMinutes(now()) < 5;
    }

    public function getOnlineStatusAttribute()
    {
        return $this->isOnline() ? 'Online' : 'Offline';
    }
    public function getOnlineStatusBadgeClassAttribute()
    {
        return $this->isOnline() ? 'badge-success' : 'badge-secondary';
    }
}
