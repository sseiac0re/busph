<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. UPDATE RESERVATIONS TABLE
        // We add columns to support Passenger Types and Round Trips
        Schema::table('reservations', function (Blueprint $table) {
            // Links a "Departure" trip to a "Return" trip (so they share one payment)
            $table->string('round_trip_group_id')->nullable()->index()->after('transaction_id');
            
            // Distinguish between One-Way and Round-Trip
            $table->string('trip_type')->default('one_way')->after('seat_number'); // 'one_way' or 'round_trip'
            
            // Distinguish Adults vs Children (for pricing)
            $table->string('passenger_type')->default('adult')->after('passenger_name'); // 'adult' or 'child'
        });

        // 2. CREATE SCHEDULE TEMPLATES TABLE
        // This allows the Admin to define rules like "Cubao to Baguio runs daily from 6am to 10pm"
        Schema::create('schedule_templates', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            
            // "Active Days" will store data like: ["Mon", "Tue", "Wed", ...]
            $table->json('active_days')->nullable(); 
            
            // Time Window & Frequency
            $table->time('start_time'); // e.g., 06:00:00
            $table->time('end_time');   // e.g., 22:00:00
            $table->integer('frequency_minutes')->default(60); // e.g., Every 60 mins
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['round_trip_group_id', 'trip_type', 'passenger_type']);
        });

        Schema::dropIfExists('schedule_templates');
    }
};