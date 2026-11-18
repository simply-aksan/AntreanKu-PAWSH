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
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['customer_phone', 'booking_date'], 'idx_appointments_phone_date');
            $table->index(['booking_date', 'status'], 'idx_appointments_date_status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['status', 'paid_at'], 'idx_transactions_status_paid_at');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index('is_active', 'idx_services_is_active');
        });

        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->index('is_active', 'idx_staff_profiles_is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('idx_appointments_phone_date');
            $table->dropIndex('idx_appointments_date_status');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_status_paid_at');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('idx_services_is_active');
        });

        Schema::table('staff_profiles', function (Blueprint $table) {
            $table->dropIndex('idx_staff_profiles_is_active');
        });
    }
};
