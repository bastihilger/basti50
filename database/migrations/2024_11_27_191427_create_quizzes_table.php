<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id')->index();
            $table->unsignedInteger('round')->index();
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id')->index();
            $table->text('text')->nullable();
            $table->text('solution')->nullable();
            $table->timestamps();
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index();
            $table->boolean('is_correct')->default(false);
            $table->text('text')->nullable();
            $table->timestamps();
        });

        Schema::create('table_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index();
            $table->unsignedBigInteger('answer_id')->nullable();
            $table->unsignedBigInteger('table_id')->index();
            $table->unsignedInteger('round')->nullable();
            $table->timestamps();
        });
    }
};
