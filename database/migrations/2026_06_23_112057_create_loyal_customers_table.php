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
        Schema::create('loyal_customers', function (Blueprint $table) {
            $table->id();
            $table->string('licence_number');
            $table->string('name');
            $table->string('branch');
            $table->string('email');
            $table->string('phone_number');
            $table->integer('booking_count')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyal_customers');
    }
};