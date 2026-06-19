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
        Schema::create('office_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('expense_type')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_number')->nullable();
            $table->string('bill_image')->nullable();
            $table->date('expense_date')->nullable();
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
        Schema::dropIfExists('office_expenses');
    }
};
