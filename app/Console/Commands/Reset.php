<?php

namespace App\Console\Commands;

use App\Models\Guest;
use App\Models\Party;
use App\Models\Quiz;
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

        foreach ($this->getQuizzes() as $quizIndex => $quizData) {
            $quiz = Quiz::create([
                'party_id' => $partyId,
                'round' => $quizIndex + 1,
            ]);

            foreach ($quizData as $questionData) {
                $question = $quiz->questions()->create([
                    'text' => $questionData['question'],
                    'solution' => $questionData['solution'],
                ]);

                foreach ($questionData['answers'] as $answerData) {
                    $question->answers()->create([
                        'text' => $answerData['text'],
                        'is_correct' => $answerData['is_correct'],
                    ]);
                }
            }
        }
    }

    protected function getQuizzes(): array
    {
        return [
            [
                [
                    'question' => 'Ihr wisst ja bestimmt, wo ich geboren bin. Aber welche dieser Städte liegt am nächsten an meinem Geburtsort? Nicht schummeln!',
                    'solution' => 'Wien ist am nächsten dran. Das sind die Entfernungen zu Lahr im Schwarzwald:<br>Wien: 628 km, London: 672 km, Rom 801 km, Barcelona 893 km',
                    'answers' => [
                        [
                            'text' => 'Barcelona',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'London',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Wien',
                            'is_correct' => true,
                        ],
                        [
                            'text' => 'Rom',
                            'is_correct' => false,
                        ],
                    ]
                ],
                [
                    'question' => 'Wisst ihr denn auch, wo Gyso geboren ist? Bestimmt! Sein Geburtsort war eine lange Zeit ein wichtiger Ort für Deutschland, aber wie lange genau?',
                    'solution' => 'Es waren 50 Jahre. Bonn war von 1949 bis 1973 provisorischer Regierungssitz und von 1973 bis 1990 Bundeshauptstadt und bis 1999 Regierungssitz der Bundesrepublik Deutschland.',
                    'answers' => [
                        [
                            'text' => '44 Jahre',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '47 Jahre',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '50 Jahre',
                            'is_correct' => true,
                        ],
                        [
                            'text' => '53 Jahre',
                            'is_correct' => false,
                        ],
                    ]
                ],
                [
                    'question' => 'Zurück zu mir. Welches dieser extrem coolen Hobbies hatte ich NICHT in meiner Kindheit?',
                    'solution' => 'Schnitzen zählte nicht zu meinen Hobbies - die anderen drei habe ich alle mal angefangen, aber keines so richtig lange beibehalten...',
                    'answers' => [
                        [
                            'text' => 'Vogelkunde',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Tischtennis',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Briefmarken Sammeln',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Schnitzen',
                            'is_correct' => true,
                        ],
                    ]
                ],
                [
                    'question' => 'Später dann habe ich natürlich Gyso nachgeeifert und Gitarre gespielt. Meine zweite E-Gitarre habe ich gekauft, weil ich die gleiche Gitarre haben wollte wie... ',
                    'solution' => 'Ich fand zwar alle vier ziemlich geil, aber von Guns \'N Roses war ich so richtig Fan, daher musste es eine Les Paul sein (allerdings nur eine Epiphone, die Gibson war zu teuer...)',
                    'answers' => [
                        [
                            'text' => ' Kirk Hammet von Metallica 🤘, also eine Flying V 🔥',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Slash von Guns \'N Roses 🤘, also eine Les Paul 🔥',
                            'is_correct' => true,
                        ],
                        [
                            'text' => 'Angus Young von AC/DC 🤘, also eine SG 🔥',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Eddie Van Halen 🤘, also eine Stratocaster 🔥',
                            'is_correct' => false,
                        ],
                    ]
                ],
                [
                    'question' => 'Da fällt mir wieder eine Frage zu Gyso ein... der hat ja gefühlt bereits mit 12 seine erste Band gehabt. Die zweite Band Manhatten spielte schon in den größten Hallen Darmstadts, und ich durfte ausnahmsweise auch auf die Konzerte, obwohl ich erst 14 war... erinnert ihr euch noch die Songtitel? Einen der folgenden gab es tatsächlich:',
                    'solution' => '"Marimba! (La la la la la)"',
                    'answers' => [
                        [
                            'text' => 'Pina Colada',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Marimba',
                            'is_correct' => true,
                        ],
                        [
                            'text' => 'Calypso',
                            'is_correct' => false,
                        ],
                        [
                            'text' => 'Margarita',
                            'is_correct' => false,
                        ],
                    ]
                ],
                [
                    'question' => 'Ich hatte in späteren Zeiten natürlich auch eine Band. Weltberühmt wie wir waren, wisst ihr bestimmt noch, wie wir hießen:',
                    'solution' => 'Natürlich 29! Weiß doch jeder!',
                    'answers' => [
                        [
                            'text' => '19 Kensington Road',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '27 Kensington Road',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '29 Kensington Road',
                            'is_correct' => true,
                        ],
                        [
                            'text' => '42 Kensington Road',
                            'is_correct' => false,
                        ],
                    ]
                ],
            ],

            [
                [
                    'question' => 'So - Berlin, da habe ich ja recht lange gelebt. Erzähl ich ja gerne. Daher wisst ihr bestimmt wie viele Jahre genau!',
                    'solution' => 'Von 1996 bis 2009 - also 13 Jahre.',
                    'answers' => [
                        [
                            'text' => '9 Jahre',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '11 Jahre',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '13 Jahre',
                            'is_correct' => true,
                        ],
                        [
                            'text' => '15 Jahre',
                            'is_correct' => false,
                        ],
                    ]
                ],
                [
                    'question' => 'So - Berlin, da habe ich ja recht lange gelebt. Erzähl ich ja gerne. Daher wisst ihr bestimmt wie viele Jahre genau!',
                    'solution' => 'Von 1996 bis 2009 - also 13 Jahre.',
                    'answers' => [
                        [
                            'text' => '9 Jahre',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '11 Jahre',
                            'is_correct' => false,
                        ],
                        [
                            'text' => '13 Jahre',
                            'is_correct' => true,
                        ],
                        [
                            'text' => '15 Jahre',
                            'is_correct' => false,
                        ],
                    ]
                ],
            ],
        ];
    }
}
