<?php



use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration

{

    public function up(): void

    {

        Schema::create('reservations', function (Blueprint $table) {

            $table->id();
            // Relationships
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            // Trip Details
            $table->integer('seat_number');
            $table->string('status')->default('pending'); // pending, confirmed
            // Payment & Transaction Info
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            // Passenger Info
            $table->string('passenger_name')->nullable();

            $table->string('discount_id_number')->nullable();
            // The Fix: Make cancellation_status nullable
            $table->string('cancellation_status')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

        });

    }
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};