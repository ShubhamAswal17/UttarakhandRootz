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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
              // Customer Details
            $table->string('customer_name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('licence_number');
             $table->string('bill_number')->unique();
            // ID Proof
            $table->string('id_proof_type');
            $table->string('id_proof_number');

            // Vehicle Details
            $table->unsignedBigInteger('vehicle_id')->nullable();

            $table->string('vehicle_name');
            $table->string('vehicle_type');
            $table->string('registration_number');
            $table->enum('payment_status', ['paid', 'unpaid','cancel'])->default('unpaid');
            // Rental
            $table->enum('rental_type', [
                'hour',
                '8hour',
                'day'
            ]);

            $table->decimal('price', 10, 2)->nullable();

            // Date Time
            

            $table->timestamps();

            // Foreign Key
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('vehicles')
                  ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
