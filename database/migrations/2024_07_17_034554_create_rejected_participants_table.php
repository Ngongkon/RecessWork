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
        Schema::create('rejected_participants', function (Blueprint $table) {
            $table->id();
            $table->string('username', 25)->unique();
            $table->string('firstname', 100)->nullable(false);
            $table->string('lastname', 100)->nullable(false);
            $table->string('email', 255)->nullable(false);
            $table->date('date_of_birth')->nullable(false);
            $table->string('school_registration_number');
            $table->foreign('school_registration_number')->references('school_registration_number')->on('schools')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejected_participants');
    }
};
