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
        $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
        $table->string('user_name');
        $table->string('vehicle_name');
        $table->string('branch')->nullable();
        $table->string('registration_number');
        $table->date('service_date');
        $table->date('insurance_upto');
        $table->date('return_date')->nullable();
        $table->text('service_issue')->nullable();
        $table->string('vendor_name')->nullable();
        $table->string('bill_image')->nullable();
        $table->string('payment_type')->nullable();
        $table->enum('payment_status', ['Pending','Paid'])->default('Pending');
        $table->decimal('service_amount', 10, 2)->nullable();
        $table->enum('service_status', ['Pending','In Progress','Completed'])->default('Pending');
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