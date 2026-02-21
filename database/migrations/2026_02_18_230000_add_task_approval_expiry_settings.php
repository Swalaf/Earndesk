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
        // Add task_approval_expiry settings to system_settings table
        // This will be stored as JSON or we can add individual columns
        
        // Option 1: Add individual columns for flexibility
        Schema::table('system_settings', function (Blueprint $table) {
            $table->boolean('task_approval_expiry_enabled')->default(false)->after('referral_bonus_amount');
            $table->integer('task_approval_expiry_value')->default(24)->after('task_approval_expiry_enabled');
            $table->enum('task_approval_expiry_unit', ['hours', 'days'])->default('hours')->after('task_approval_expiry_value');
            $table->enum('task_approval_expiry_action', ['auto_approve', 'expire'])->default('auto_approve')->after('task_approval_expiry_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropColumn([
                'task_approval_expiry_enabled',
                'task_approval_expiry_value',
                'task_approval_expiry_unit',
                'task_approval_expiry_action',
            ]);
        });
    }
};
