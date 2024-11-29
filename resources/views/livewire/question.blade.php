<?php

use App\Models\TableAnswer;
use function Livewire\Volt\{state, mount, computed};


state([
    'guest',
    'question',
    'questionIndex',
    'tableAnswer',
    'answer' => 0,
]);


mount(function () {
    $this->checkTableAnswer();
});

$isLockable = computed(function () {
    return $this->answer > 0;
});

$checkTableAnswer = function () {
    $this->tableAnswer = TableAnswer::firstOrCreate([
        'question_id' => $this->question->id,
        'table_id' => $this->guest->currentTable()->id,
    ]);

    $this->answer = $this->tableAnswer->answer_id ?: 0;
};

$lock = function () {
    $this->tableAnswer->update(['answer_id' => $this->answer]);

    $this->checkTableAnswer();
};


?>

<div class="grid gap-4">
    <div class="font-bold">
        Frage {{ $this->questionIndex + 1 }}:
    </div>

    <div>
        {!! $this->question->text !!}
    </div>

    <div class="grid gap-8">
        <flux:radio.group wire:model.live="answer" variant="cards" class="max-sm:flex-col">
            @foreach($this->question->answers as $answer)
                <flux:radio :value="$answer->id" :label="$answer->text"/>
            @endforeach
        </flux:radio.group>

        @if(!$this->tableAnswer->isAnswered())
            <flux:button
                variant="danger" wire:click="lock"
                class="w-full {{ !$this->isLockable ? 'opacity-40 pointer-events-none' : '' }}"
            >
                Einloggen
            </flux:button>
        @else
            <flux:card class="space-y-6 text-left">
                <div>
                    <div class="font-bold {{ $this->tableAnswer->isCorrect() ? 'text-green-500' : 'text-red-500' }}">
                        {{ $this->tableAnswer->isCorrect() ? 'Richtig! 🎉' : 'Leider Falsch! 🥲' }}
                    </div>

                    <div>
                        {!! $this->question->solution !!}
                    </div>
                </div>
            </flux:card>
        @endif
    </div>
</div>
