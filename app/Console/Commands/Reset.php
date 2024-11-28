<?php

namespace App\Console\Commands;

use App\Models\Guest;
use App\Models\Party;
use App\Models\Round;
use App\Models\Table;
use App\Models\TableImage;
use Illuminate\Console\Command;

class Reset extends Command
{
    protected $signature = 'reset';

    public function handle()
    {
        $this->call('migrate:fresh');

        $roundCount = 2;
        $guestCount = 41;
        $tableCount = 7;

        Party::create([
            'id' => 1,
        ]);

        for ($roundId = 1; $roundId <= $roundCount; $roundId++) {
            Round::create([
                'id' => $roundId,
                'round' => $roundId,
                'party_id' => 1,
                'is_current' => $roundId === 1,
            ]);
        }

        for ($guestId = 1; $guestId <= $guestCount; $guestId++) {
            Guest::create([
                'party_id' => 1,
                'id' => $guestId,
            ]);
        }

        for ($tableId = 1; $tableId <= $tableCount; $tableId++) {
            $seatCount = 6;
            if ($tableId === 1) {
                $seatCount = 5;
            }

            Table::create([
                'id' => $tableId,
                'party_id' => 1,
                'seat_count' => $seatCount,
            ]);

            for ($roundId = 1; $roundId <= $roundCount; $roundId++) {
                for ($seatId = 1; $seatId <= $seatCount; $seatId++) {
                    TableImage::create([
                        'table_id' => $tableId,
                        'round_id' => $roundId,
                    ]);
                }
            }
        }

        for ($guestId = 1; $guestId <= $guestCount; $guestId++) {
            $guest = Guest::find($guestId);
            for ($roundId = 1; $roundId <= $roundCount; $roundId++) {
                $tableImageQuery = TableImage::query()
                    ->doesntHave('guestTableImages')->where('round_id', $roundId)->inRandomOrder();

                if ($guestId <= 5) {
                    $tableImageQuery->where('table_id', 1);
                }

                $guest->guestTableImages()->create([
                    'table_image_id' => $tableImageQuery->first()->id,
                    'round_id' => $roundId,
                ]);
            }
        }
    }
}
