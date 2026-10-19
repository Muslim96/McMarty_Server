<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FineReason extends Model
{
    protected $fillable = ['name', 'default_amount', 'is_active'];

    protected function casts(): array
    {
        return ['default_amount' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }
}
