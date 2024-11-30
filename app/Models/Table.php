<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends BaseModel
{
    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function tableImages(): HasMany
    {
        return $this->hasMany(TableImage::class);
    }

    public function guestsForRound($round)
    {
        return $this->tableImages()->where('round', $round)->get()->map(function (TableImage $tableImage) {
            return $tableImage->guest;
        });
    }

    public function hasAnsweredQuestion($questionId): bool
    {
        return TableAnswer::where('table_id', $this->id)->where('question_id', $questionId)->whereNotNull('answer_id')
            ->exists();
    }

    public function answerForQuestion($questionId): Answer
    {
        $tableAnswer = TableAnswer::where('table_id', $this->id)->where('question_id', $questionId)->first();

        return Answer::find($tableAnswer->answer_id);
    }

    public function hasAnsweredQuestionCorrectly($questionId): bool
    {
        if (!$this->hasAnsweredQuestion($questionId)) {
            return false;
        }

        return TableAnswer::where('table_id', $this->id)->where('question_id', $questionId)->first()?->isCorrect();
    }
}
