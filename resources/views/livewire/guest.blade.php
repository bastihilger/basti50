<?php

use App\Models\Guest;
use App\Models\Party;
use App\Models\User;
use Livewire\Attributes\On;
use function Livewire\Volt\{rules, state, mount, on, computed};


state([
    'guest',
    'round' => 1,
    'step' => 1,
    'name' => '',
]);

rules([
    'name' => 'required',
]);

mount(function () {
    $this->round = $this->guest->party->current_round;
    $this->step = $this->guest->current_step;
    $this->name = $this->guest->name;
});

on(['echo:party,RoundUpdated' => function ($event) {
    $this->round = $event['round'];
    $this->guest->refresh();
    $this->step = $this->guest->current_step;
}]);

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
    $this->continue();
};

$stepBack = function () {
    $this->step--;
    $this->guest->update(['current_step' => $this->step]);
}


?>

<div class="max-w-screen-sm mx-auto text-center grid gap-12">
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

                <div>
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

                <div>
                    <flux:button wire:click="continue">
                        Weiter geht's!
                    </flux:button>
                </div>
            </div>
        @endif

    @elseif($this->step === 2)
        <div class="grid gap-12">
            <div class="grid gap-4">
                @if($this->round === 1)
                    <p>Hallo {{ $this->name }}!</p>
                    <p>
                        Bevor es
                        <span class="text-3xl">🍖 🥬 👨‍🍳</span>
                        gibt, musst du noch deinen Tisch suchen - dort, wo du das hier findest, da lasse dich nieder:
                    </p>
                @else
                    <p>Du hast es geahnt - du musst wieder suchen:</p>
                @endif
            </div>

            <div class="w-full aspect-square">
                <img src="{{ $this->currentImage }}" class="w-full object-cover rounded-full"/>
            </div>

            <div>
                <flux:button wire:click="continue">
                    Gefunden? Weiter &rarr;
                </flux:button>
            </div>
        </div>
    @endif
</div>
