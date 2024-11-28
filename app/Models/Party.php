<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends BaseModel
{
    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function currentRound(): int
    {
        return $this->rounds()->where('is_current', true)->first()->round ?? 1;
    }

    public function switchRound($round): void
    {
        $this->rounds()->where('is_current', true)->first()?->update(['is_current' => false]);
        $this->rounds()->where('round', $round)->first()?->update(['is_current' => true]);
    }
}
