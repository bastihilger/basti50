<?php

use App\Events\RoundUpdated;
use App\Models\Guest;
use App\Models\Party;
use App\Models\User;
use function Livewire\Volt\{state, mount, updated, on};


state([
    'party',
    'round' => 1,
]);

mount(function () {
    $this->round = $this->party->current_round;
});

on(['echo:party,NameUpdated' => function ($event) {
    $this->party->refresh();
}]);

on(['echo:party,TableAnswerUpdated' => function ($event) {
    $this->party->refresh();
}]);

updated([
    'round' => function ($round) {
        if ($round > $this->party->current_round) {
            $this->party->guests->each(function ($guest) {
                $guest->update(['current_step' => 2]);
            });
        }

        $this->party->update(['current_round' => $round]);

        $this->party->refresh();

        RoundUpdated::dispatch($this->party->id, $round);
    }
]);
?>

<div class="max-w-screen-sm mx-auto grid gap-8 text-center">
    <div>
        <flux:radio.group wire:model.live="round" label="Runde" variant="segmented">
            @for($r = 1; $r <= $this->party->rounds; $r++)
                <flux:radio :value="$r" :label="$r"/>
            @endfor
        </flux:radio.group>
    </div>

    <div class="grid gap-12">
        @foreach($this->party->tables as $tableIndex => $table)
            <div class="grid gap-4">
                <div>
                    Tisch {{ $tableIndex + 1  }}
                </div>

                <div>
                    <img src="{{ $table->path }}" class="w-full h-auto"/>
                </div>

                <div class="grid gap-4">
                    @for($round = 1; $round <= $this->party->rounds; $round++)
                        <flux:card class="space-y-6 text-left">
                            <div>
                                Runde {{ $round }}:
                            </div>

                            <div>
                                @foreach($table->guestsForRound($round) as $guest)
                                    <div>
                                        {{ $guest->name ?: '🤔' }}
                                    </div>
                                @endforeach
                            </div>

                            <div>
                                @foreach(
                                    $this->party->quizzes()->where('round', $round)->first()->questions
                                    as $questionIndex => $question
                                )
                                    <div>
                                        Frage {{ $questionIndex + 1 }}:<br>
                                        @if($table->hasAnsweredQuestion($question->id))
                                            Antwort: {{ $table->answerForQuestion($question->id)?->text }} =
                                            @if($table->hasAnsweredQuestionCorrectly($question->id))
                                                <span class="text-green-500">Korrekt</span>
                                            @else
                                                <span class="text-red-500">Falsch</span>
                                            @endif
                                        @else
                                            ?
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </flux:card>
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
</div>
