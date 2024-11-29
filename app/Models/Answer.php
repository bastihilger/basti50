<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends BaseModel
{
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
