<?php

use App\Models\Answer;
use App\Models\Participant;
use App\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Participant::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Question::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(Answer::class)->constrained()->cascadeOnUpdate();
            $table->integer('marks_obtained');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
