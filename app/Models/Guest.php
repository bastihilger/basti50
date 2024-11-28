<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends BaseModel
{
    public function party() : BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function guestTableImages() : HasMany
    {
        return $this->hasMany(GuestTableImage::class, 'guest_id', 'id');
    }
}