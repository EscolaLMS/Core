<?php

namespace EscolaLms\Core\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles, HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_token',
    ];

    protected $appends = [
        'avatar_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'onboarding_completed' => 'boolean',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getEmailVerifiedAttribute(): bool
    {
        return $this->hasVerifiedEmail();
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->path_avatar ? Storage::url($this->path_avatar) : null;
    }

    public function guardName(): string
    {
        return 'api';
    }

    public function getMorphClass()
    {
        return config('auth.providers.users.model', self::class);
    }
}
