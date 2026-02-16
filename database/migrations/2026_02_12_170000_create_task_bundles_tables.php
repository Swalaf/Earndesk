<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Task bundles table (already exists in migration, ensuring structure)
        if (!Schema::hasTable('task_bundles')) {
            Schema::create('task_bundles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 200); // Bundle name (e.g., "Instagram Engagement Bundle")
                $table->text('description')->nullable();
                $table->string('category', 50); // Category for grouping
                $table->string('platform', 50)->nullable(); // Platform (instagram, tiktok, etc.)
                $table->decimal('total_reward', 15, 2); // Total reward for bundle
                $table->decimal('total_quantity', 10, 0); // Total number of tasks in bundle
                $table->integer('completed_count')->default(0); // Tasks completed
                $table->decimal('worker_reward_per_task', 10, 2); // Reward per mini task
                $table->string('status', 20)->default('active'); // active, completed, expired
                $table->string('difficulty_level', 20)->default('easy');
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // Task bundle items pivot table (links mini tasks to bundles)
        if (!Schema::hasTable('task_bundle_items')) {
            Schema::create('task_bundle_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bundle_id');
                $table->unsignedBigInteger('task_id');
                $table->integer('order')->default(0); // Display order within bundle
                $table->timestamps();

                $table->foreign('bundle_id')->references('id')->on('task_bundles')->onDelete('cascade');
                $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');

                $table->unique(['bundle_id', 'task_id']);
            });
        }

        // Update tasks table to mark if task is bundled
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'is_bundled')) {
                $table->boolean('is_bundled')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('tasks', 'bundle_id')) {
                $table->unsignedBigInteger('bundle_id')->nullable()->after('is_bundled');
                $table->foreign('bundle_id')->references('id')->on('task_bundles')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['bundle_id']);
            $table->dropColumn(['is_bundled', 'bundle_id']);
        });
        Schema::dropIfExists('task_bundle_items');
        Schema::dropIfExists('task_bundles');
    }
};
