<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id')->index();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('guest_table_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guest_id')->index();
            $table->unsignedBigInteger('table_image_id')->index();
            $table->unsignedBigInteger('round_id')->index();
            $table->timestamps();
        });
    }
};
