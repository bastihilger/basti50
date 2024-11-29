<?php

use App\Events\NameUpdated;
use App\Models\Guest;
use App\Models\Party;
use App\Models\User;
use Livewire\Attributes\On;
use function Livewire\Volt\{rules, state, mount, on, computed};


state([
    'guest',
    'round' => 1,
    'step' => 1,
    'otherGuests' => [],
    'quiz',
    'name' => '',
]);

rules([
    'name' => 'required',
]);

mount(function () {
    $this->round = $this->guest->party->current_round;
    $this->step = $this->guest->current_step;
    $this->name = $this->guest->name;
    $this->updateOtherGuests();
    $this->refreshQuiz();
});

on(['echo:party,RoundUpdated' => function ($event) {
    $this->round = $event['round'];
    $this->guest->refresh();
    $this->refreshQuiz();
    $this->step = $this->guest->current_step;
}]);

on(['echo:party,NameUpdated' => function ($event) {
    $this->updateOtherGuests();
}]);

$refreshQuiz = function () {
    $this->quiz = $this->guest->currentQuiz();
};

$updateOtherGuests = function () {
    $this->otherGuests = [];
    $this->guest->refresh();

    foreach ($this->guest->party->guests as $otherGuest) {
        if (
            $otherGuest->id !== $this->guest->id
            && $otherGuest->currentTable()->id === $this->guest->currentTable()->id
        ) {
            $this->otherGuests[] = $otherGuest;
        }
    }
};

$currentImage = computed(function () {
    return $this->guest->tableImages()->where('round', $this->round)->first()?->path;
});

$continue = function () {
    $this->step++;
    $this->guest->update(['current_step' => $this->step]);
};

$enterName = function () {
    $this->validateOnly('name');
    $this->guest->update(['name' => $this->name]);
    NameUpdated::dispatch($this->guest->id);
    $this->continue();
};

$stepBack = function () {
    $this->step--;
    $this->guest->update(['current_step' => $this->step]);
}
?>

<div
    class="max-w-screen-sm mx-auto text-center grid gap-12 pb-20"
    x-data="{
        jumpToTop() {
            window.scrollTo(0,0)
        },
    }"
>
    @if($this->step > 1)
        <div>
            <flux:button wire:click="stepBack" size="xs">
                &larr; zurück
            </flux:button>
        </div>
    @endif

    @if($this->step === 1)
        @if($this->round === 1)
            <div class="grid gap-8">
                <div class="grid gap-2">
                    <p>Hallo!<br>Schön, dass du mit mir feierst!</p>
                    <p class="text-3xl"> 🤗 🎉 🍾</p>
                </div>

                <div>
                    <flux:field>
                        <flux:label>Wer bist du denn - schreib hier mal deinen Namen hin:</flux:label>

                        <flux:input wire:model="name"/>

                        <flux:error name="name"/>
                    </flux:field>
                </div>

                <div x-on:click="jumpToTop">
                    <flux:button wire:click="enterName">
                        Weiter geht's!
                    </flux:button>
                </div>
            </div>
        @else
            <div class="grid gap-8">
                <div class="grid gap-2">
                    <p>Nun heißt es Abschied nehmen und neuen Platz suchen!</p>
                </div>

                <div x-on:click="jumpToTop">
                    <flux:button wire:click="continue">
                        Weiter geht's!
                    </flux:button>
                </div>
            </div>
        @endif

    @elseif($this->step === 2)
        <div class="grid gap-12 w-full max-w-screen-sm">
            <div class="grid gap-4">
                @if($this->round === 1)
                    <p>Hallo {{ $this->name }}!</p>
                    <p>
                        Bevor es
                        <span class="text-3xl">🍖 🥬 👨‍🍳</span>
                        gibt, musst du noch deinen Tisch suchen.
                        <br><br>

                        Dort, wo du das hier unten findest, da lasse dich nieder:
                    </p>
                @else
                    <p>Du hast es geahnt - du musst wieder suchen:</p>
                @endif
            </div>

            <div class="w-full max-w-96 aspect-square mx-auto">
                <img src="{{ $this->currentImage }}" class="w-full h-full object-cover rounded-full"/>
            </div>

            <div x-on:click="jumpToTop">
                <flux:button wire:click="continue">
                    Gefunden? Weiter &rarr;
                </flux:button>
            </div>
        </div>

    @elseif($this->step === 3)
        <div class="grid gap-12 w-full max-w-screen-sm">
            <div>
                Du sitzt am Tisch zusammen mit:<br>
                @foreach($this->otherGuests as $i => $o)
                    {{ $o->name ?: '🤔' }}{{
                                $i < count($this->otherGuests)-1 ? ($i < count($this->otherGuests)-2 ? ',' : ' und ') : ''
                            }}
                @endforeach
            </div>

            <div>
                @if($this->round === 1)
                    Damit euch nicht langweilig wird, hier ein paar Quizfragen. Heute geht es nur um mich! Mal sehen,
                    wie gut ihr mich kennt 😅<br>
                    Achtung: Alle am Tisch raten zusammen. Wenn einer die Frage einloggt, loggt er sie für alle ein!y
                @else
                    Weiter geht's mit dem Quiz - <strong>Runde {{ $this->round }}</strong>
                @endif
            </div>

            <div class="grid gap-8">
                <div class="grid gap-8">
                    @foreach($this->quiz->fresh()->questions as $questionIndex => $question)
                        <div>
                            <livewire:question
                                :wire:key="$question->id"
                                :question="$question" :questionIndex="$questionIndex" :guest="$guest"
                            />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
