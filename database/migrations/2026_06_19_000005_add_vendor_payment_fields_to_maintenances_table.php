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
        Schema::table('maintenances', function (Blueprint $table) {
            $table->string('vendor_name')->nullable()->after('service_issue');
            $table->string('bill_image')->nullable()->after('vendor_name');
            $table->string('payment_type')->nullable()->after('bill_image');
            $table->string('payment_status')->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn(['vendor_name', 'bill_image', 'payment_type', 'payment_status']);
        });
    }
};