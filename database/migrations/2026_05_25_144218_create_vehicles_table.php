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
        Schema::create('vehicles', function (Blueprint $table) {

            $table->id();

            // Logged in user id
            $table->unsignedBigInteger('user_id');
            // Vehicle details
            $table->string('vehicle_name');
            $table->string('vehicle_type');
            $table->string('seating_capacity');
            $table->string('additional_features')->nullable();
            $table->string('registration_number') ->unique();
            $table->string('brand')->nullable();;
            $table->string('model')->nullable();;
            $table->string('fuel_type');
            $table->decimal('rate_per_hour', 10, 2);
            $table->decimal('rate_max_8hour', 10, 2);
            $table->decimal('rate_per_day', 10, 2);
            $table->string('vehicle_image')->nullable();               
            $table->text('description')->nullable();
            $table->string('status')->default('Available');
            $table->datetime('insurence_upto')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
