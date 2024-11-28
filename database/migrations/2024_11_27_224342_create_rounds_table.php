<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('round');
            $table->unsignedBigInteger('party_id')->index();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }
};
