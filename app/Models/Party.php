<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends BaseModel
{
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
