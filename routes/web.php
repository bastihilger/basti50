<?php

use App\Models\Guest;
use App\Models\Party;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

//Volt::route('/guest/{guestId}', 'guest')->name('guest');

//Route::get('/guest/{guestId}', [GuestController::class, 'index'])->name('guest');

Route::get('/guest/{guest}', function (Guest $guest) {
    return view('guest', ['guest' => $guest]);
});

Route::get('/admin/{party}', function (Party $party) {
    return view('admin', ['party' => $party]);
});

Route::get('/qr/{party}', function (Party $party) {

    $data = [
        'party' => $party,
    ];

    $pdf = Pdf::loadView('pdf.qrcodes', $data);

    return $pdf->download('qrcodes-' . now()->timestamp . '.pdf');

});
