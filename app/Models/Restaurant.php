<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Restaurant extends Model
{
    protected $fillable = ['slug', 'name', 'logo'];

    public function employers(): HasMany
    {
        return $this->hasMany(Employer::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? Storage::disk('public')->url($this->logo) : null;
    }

    public function scopeVisibleTo(Builder $q, User $u): Builder
    {
        $r = $u->restrictedRestaurantId();

        return $r === null ? $q : $q->where('id', $r);
    }
}
