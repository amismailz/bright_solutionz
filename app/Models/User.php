<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use App\Enums\RoleTypeEnum;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
    use HasApiTokens, HasRoles, Notifiable, SoftDeletes, HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'otp',
        'otp_expires_at',
        'association_id',
        'category_id',
        'status',
        'login_status',
        'phone_verified_at',
        'last_login',
        'device_token',
        'device_type',
        'device_model',
        'range_id',
        'avatar',
        'is_phone_verified',
        'is_email_verified',
        'username',
        'disallow_location_track',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'otp_expires_at'     => 'datetime',
        'phone_verified_at'  => 'datetime',
        'last_login'         => 'datetime',
        'is_phone_verified'  => 'boolean',
        'is_email_verified'  => 'boolean',
        'email_verified_at'  => 'datetime',
        'disallow_location_track' => 'boolean',
    ];


    function isAdmin(): bool
    {
        return $this->hasRole(RoleTypeEnum::SuperAdmin->value);
    }

    function isDistributor(): bool
    {
        return $this->hasRole(RoleTypeEnum::Distributor->value);
    }


    function isSupervisor(): bool
    {
        return $this->hasRole(RoleTypeEnum::Supervisor->value);
    }
    function isAdminSupervisor(): bool
    {
        return $this->hasRole(RoleTypeEnum::AdminSupervisor->value);
    }
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function setIsActiveAttribute(bool $value): void
    {
        $this->attributes['status'] = $value ? 'active' : 'inactive';
    }

    public function getRole(): ?string
    {
        return $this->getRoleNames()->first(); // returns the first assigned role name
    }
    public function association()
    {
        return $this->belongsTo(Association::class);
    }
    public function range()
    {
        return $this->belongsTo(Range::class);
    }

    public function points()
    {
        return $this->belongsToMany(Point::class, 'user_points', 'user_id', 'point_id');
    }


    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : null;
    }
}
