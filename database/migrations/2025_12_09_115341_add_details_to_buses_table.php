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
    Schema::table('buses', function (Blueprint $table) {
        $table->string('plate_number')->nullable()->after('bus_number');
        $table->string('status')->default('active')->after('capacity'); // active, maintenance, retired
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            //
        });
    }
};
