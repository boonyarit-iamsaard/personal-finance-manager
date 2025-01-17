<?php

namespace App\Models;

use App\Enums\Role;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        /**
         * Create a default wallet for each new user.
         *
         * When a new user is registered, automatically create a "Cash" wallet
         * to help them start managing their finances immediately.
         */
        static::created(function (User $user) {
            $name = 'Cash';
            $slug = Str::slug($name);

            $user->wallets()->create([
                'name' => $name,
                'slug' => $slug,
            ]);
        });
    }

    /**
     * @return HasMany<Wallet, covariant User>
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function hasAdministrativeAccess(): bool
    {
        // FIXME: fix this phpstan issue with the backed enum
        // @phpstan-ignore-next-line
        return $this->role === Role::ADMINISTRATOR;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasAdministrativeAccess();
        }

        return false;
    }

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
            'role' => Role::class,
        ];
    }
}
