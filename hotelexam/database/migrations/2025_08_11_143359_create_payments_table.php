<?php

use App\Status;
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
        // Create the payments table 
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservations_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('paid', $total = 8, $places = 2);
            $table->enum('status', Status::cases());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
