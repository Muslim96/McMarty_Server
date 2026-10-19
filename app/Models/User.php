<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role_id', 'restaurant_id', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function hasAbility(string $ability): bool
    {
        if (! $this->is_active) {
            return false;
        }
        $abilities = config('admin.roles.' . $this->role?->slug, []);

        return in_array('*', $abilities, true) || in_array($ability, $abilities, true);
    }

    /** ID ресторана, которым ограничен пользователь (null = видит все). */
    public function restrictedRestaurantId(): ?int
    {
        return $this->hasRole('manager') ? ($this->restaurant_id ?? 0) : null;
    }

    public function canAccessRestaurant(?int $restaurantId): bool
    {
        $r = $this->restrictedRestaurantId();

        return $r === null || $r === (int) $restaurantId;
    }
}
