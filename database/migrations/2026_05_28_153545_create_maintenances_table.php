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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('vehicle_name');
             $table->string('registration_number');
            $table->date('service_date');
            $table->date('insurance_upto');
            $table->date('return_date')->nullable();
            $table->text('service_issue')->nullable();
            $table->decimal('service_amount', 10, 2)->nullable();
            $table->string('service_status')->enum('Pending', 'In Progress', 'Completed')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
