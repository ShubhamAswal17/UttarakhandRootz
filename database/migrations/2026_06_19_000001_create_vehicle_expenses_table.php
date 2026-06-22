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
        Schema::create('vehicle_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('employee_branch')->nullable();
            $table->string('vehicle_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('expense_type')->nullable();
            $table->date('expense_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('bill_image')->nullable();
            $table->text('expense_description')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('expense_amount', 12, 2)->nullable();
            $table->string('expense_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_expenses');
    }
};
