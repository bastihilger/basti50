<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id')->index();
            $table->unsignedInteger('current_step')->default(1);
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }
};
