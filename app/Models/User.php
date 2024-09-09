<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia, HasAvatar
{
    use HasFactory, Notifiable, HasRoles, HasPanelShield, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_writer',
        'commission',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_writer' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->email_verified_at = now();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars'); // Poza de profil
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this?->getFirstMediaUrl('avatars');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail();
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_shift_user')->withTimestamps()->using(LocationShiftUser::class);
    }
    public function shifts()
    {
        return $this->belongsToMany(Shift::class, 'location_shift_user')->withTimestamps()->using(LocationShiftUser::class);
    }

    public function shift()
    {
        return $this->hasMany(Shift::class);
    }
}
