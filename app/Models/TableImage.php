<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableImage extends BaseModel
{
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function tableModel(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
