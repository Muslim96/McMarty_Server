<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fine extends Model
{
    use SoftDeletes;

    public const ACTIVE = 'active';
    public const PAID = 'paid';
    public const CANCELLED = 'cancelled';

    public const STATUSES = [
        self::ACTIVE    => ['label' => 'К оплате',  'color' => 'warning'],
        self::PAID      => ['label' => 'Оплачен',   'color' => 'success'],
        self::CANCELLED => ['label' => 'Отменён',   'color' => 'secondary'],
    ];

    protected $fillable = [
        'employer_id', 'fine_reason_id', 'issued_by', 'amount', 'comment',
        'fined_at', 'status', 'paid_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'       => 'decimal:2',
            'fined_at'     => 'date',
            'paid_at'      => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(FineReason::class, 'fine_reason_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUSES[$this->status]['color'] ?? 'secondary';
    }

    public function scopeVisibleTo(Builder $q, User $u): Builder
    {
        $r = $u->restrictedRestaurantId();

        return $r === null ? $q : $q->whereHas('employer', fn ($e) => $e->where('restaurant_id', $r));
    }
}
