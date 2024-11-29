<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableAnswer extends BaseModel
{
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function table(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function isAnswered(): bool
    {
        return $this->answer_id !== null;
    }

    public function isCorrect(): bool
    {
        ray('a ' . $this->answer_id);
        ray('b ' . $this->question->answers()->where('is_correct', true)->first()->id);
        return $this->answer_id === $this->question->answers()->where('is_correct', true)->first()->id;
    }
}
