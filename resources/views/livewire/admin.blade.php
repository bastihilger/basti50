<?php

use App\Events\RoundUpdated;
use App\Models\Guest;
use App\Models\Party;
use App\Models\User;
use function Livewire\Volt\{state, mount, updated};


state([
    'party',
    'round' => 1,
]);

mount(function () {
    $this->round = $this->party->current_round;
});

updated([
    'round' => function ($round) {
        if ($round > $this->party->current_round) {
            $this->party->guests->each(function ($guest) {
                $guest->update(['current_step' => 2]);
            });
        }

        $this->party->update(['current_round' => $round]);

        RoundUpdated::dispatch($this->party->id, $round);
    }
]);
?>

<div class="max-w-screen-sm mx-auto">
    <div>
        <flux:radio.group wire:model.live="round" label="Runde" variant="segmented">
            @for($r = 1; $r <= $this->party->rounds; $r++)
                <flux:radio :value="$r" :label="$r"/>
            @endfor
        </flux:radio.group>
    </div>
</div>
