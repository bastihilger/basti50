<?php

use App\Events\RoundUpdated;
use App\Models\Guest;
use App\Models\Party;
use App\Models\User;
use function Livewire\Volt\{state, mount, updated};


state([
    'party',
    'round' => 1,
    'possibleRounds' => 1,
]);

mount(function () {
    $this->round = $this->party->currentRound();
    $this->possibleRounds = $this->party->rounds->count();
});

updated([
    'round' => function ($round) {
        $this->party->switchRound($round);

        RoundUpdated::dispatch($this->party->id, $round);
    }
]);
?>

<div class="max-w-screen-sm mx-auto">
    <div>
        <flux:radio.group wire:model.live="round" label="Runde" variant="segmented">
            @for($r = 1; $r <= $this->possibleRounds; $r++)
                <flux:radio :value="$r" :label="$r"/>
            @endfor
        </flux:radio.group>
    </div>
</div>
