<?php

namespace EscolaLms\Core\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $current_timezone
 * @property string $first_name
 * @property string $last_name
 * @property string $path_avatar
 * @property string $email
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles, HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_reset_token',
    ];

    /**
     * @var array<int, string>
     */
    protected $appends = [
        'avatar_url',
    ];

    /**
     * @var array<string, string>
     */
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
