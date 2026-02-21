<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing foreign key constraint
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        // Add new foreign key constraint referencing marketplace_categories
        Schema::table('digital_products', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('marketplace_categories')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('digital_products', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('task_categories')
                ->onDelete('set null');
        });
    }
};
