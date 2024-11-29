<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends BaseModel
{
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
