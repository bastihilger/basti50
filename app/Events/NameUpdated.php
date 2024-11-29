<?php

namespace App\Events;

use App\Models\Guest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NameUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $currentTableId;

    public function __construct(
        public int $guestId,
    )
    {
        $this->currentTableId = Guest::find($this->guestId)->currentTable()->id;
    }

    public function broadcastOn(): array
    {

        return [
            new Channel('party'),
        ];
    }
}
