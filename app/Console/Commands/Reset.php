<?php

namespace App\Console\Commands;

use App\Models\Guest;
use App\Models\Party;
use App\Models\Table;
use App\Models\TableImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Reset extends Command
{
    protected $signature = 'reset';

    public function handle()
    {
        $this->call('migrate:fresh');

        $imgPaths = collect(Storage::disk('public')->files('img/table-img'));
        $usedImages = collect([]);

        $roundCount = 2;
        $guestCount = 41;
        $tableCount = 7;
        $partyId = 1;

        Party::create([
            'id' => $partyId,
            'current_round' => 1,
            'rounds' => $roundCount,
        ]);

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

            for ($round = 1; $round <= $roundCount; $round++) {
                for ($seatId = 1; $seatId <= $seatCount; $seatId++) {
                    $imgPath = $imgPaths->filter(function ($path) use (&$usedImages, $partyId, $tableId) {
                        return !$usedImages->contains($path)
                            && Str::contains($path, '/' . $partyId . '_' . $tableId . '_');
                    })->first();

                    $usedImages->push($imgPath);

                    TableImage::create([
                        'table_id' => $tableId,
                        'round' => $round,
                        'path' => '/' . $imgPath,
                    ]);
                }
            }
        }

        for ($guestId = 1; $guestId <= $guestCount; $guestId++) {
            for ($round = 1; $round <= $roundCount; $round++) {
                $tableImageQuery = TableImage::query()
                    ->doesntHave('guest')->where('round', $round)->inRandomOrder();

                if ($guestId <= 5) {
                    $tableImageQuery->where('table_id', 1);
                }

                $tableImage = $tableImageQuery->first();

                $tableImage->update(['guest_id' => $guestId]);
            }
        }
    }
}
