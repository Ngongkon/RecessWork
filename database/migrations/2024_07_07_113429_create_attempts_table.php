<?php

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
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->integer('score');
            $table->string('school_registration_number');
            $table->integer('challengeName');
            $table->foreign('school_registration_number')->references('school_registration_number')->on('schools')->onDelete('cascade');
            $table->foreign('username')->references('username')->on('participants')->onDelete('cascade');
            $table->foreign('challengeName')->references('challengeName')->on('challenges')->onDelete('cascade');
            $table->timestamps();
            
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
