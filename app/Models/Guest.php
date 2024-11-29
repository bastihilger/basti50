<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Guest extends BaseModel
{
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function tableImages(): HasMany
    {
        return $this->hasMany(TableImage::class);
    }

    public function url(): string
    {
        return config('app.live_url') . '/guest/' . $this->id;
    }

    public function qr(): string
    {
        $filename = $this->party_id . '_' . $this->id . '.png';

        $path = 'img/qr/' . $filename;

        $savedImage = Storage::disk('public')->get($path);

        if (!$savedImage) {
            $image = QrCode::format('png')
                ->size(400)->errorCorrection('H')
                ->generate($this->url());

            Storage::disk('public')->put($path, $image);
        }

        return $path;
    }
}
