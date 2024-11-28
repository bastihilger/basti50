<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class TableImage extends BaseModel
{
    public function guestTableImages() : HasMany
    {
        return $this->hasMany(GuestTableImage::class, 'table_image_id', 'id');
    }
}
