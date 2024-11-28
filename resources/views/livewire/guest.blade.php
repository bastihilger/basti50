<?php

use App\Models\Guest;
use App\Models\Party;
use App\Models\User;
use Livewire\Attributes\On;
use function Livewire\Volt\{state, mount, on};


state([
    'guest',
    'round' => 1,
    'name' => '',
]);

mount(function () {
    $this->round = $this->guest->party->currentRound();
    $this->name = $this->guest->name;
});

on(['echo:party,RoundUpdated' => function ($event) {
    $this->round = $event['round'];
}]);


?>

<div class="max-w-screen-sm mx-auto text-center">
    @if($this->round === 1)
        <div class="grid gap-8">
            <div class="grid gap-2">
                <p>Hallo, willkommen und schön, dass du da bist!</p>
                <p class="text-3xl"> 🤗</p>
            </div>

            <div class="text-lg">
                <flux:field>
                    <flux:label>Wer bis du denn - schreibe bitte hier deinen Namen:</flux:label>

                    <flux:input wire:model="name"/>
                </flux:field>
            </div>
        </div>
    @endif
</div>
