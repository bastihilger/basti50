<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id')->index();
            $table->unsignedInteger('seat_count')->default(1);
            $table->string('path');
            $table->timestamps();
        });

        Schema::create('table_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_id')->index();
            $table->unsignedBigInteger('round')->index();
            $table->unsignedBigInteger('guest_id')->nullable()->index();
            $table->string('path');
            $table->timestamps();
        });
    }
};
