<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableImage extends BaseModel
{
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }
}
