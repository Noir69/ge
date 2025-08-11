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
        // Create the rooms table 
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotels_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('room_name');
            $table->integer('capacity');
            $table->decimal('rate', $total = 8, $places = 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
