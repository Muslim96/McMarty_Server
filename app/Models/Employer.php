<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    protected $fillable = [
        'surname', 'name', 'patronymic', 'birth_date', 'phone', 'email',
        'position', 'hired_at', 'is_active', 'restaurant_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'hired_at'   => 'date',
            'is_active'  => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([$this->surname, $this->name, $this->patronymic]))) ?: '—';
    }

    public function getInitialsAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->surname ?: $this->name ?: '?', 0, 1) . mb_substr($this->name ?: '', 0, 1));
    }

    public function scopeVisibleTo(Builder $q, User $u): Builder
    {
        $r = $u->restrictedRestaurantId();

        return $r === null ? $q : $q->where('restaurant_id', $r);
    }

    public function scopeSearch(Builder $q, string $s): Builder
    {
        return $q->where(fn ($w) => $w->where('surname', 'like', "%$s%")
            ->orWhere('name', 'like', "%$s%")
            ->orWhere('patronymic', 'like', "%$s%")
            ->orWhere('phone', 'like', "%$s%")
            ->orWhere('email', 'like', "%$s%"));
    }
}
