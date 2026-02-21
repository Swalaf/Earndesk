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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('marketplace_categories')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'internship']);
            $table->enum('experience_level', ['entry', 'intermediate', 'expert']);
            $table->decimal('budget_min', 12, 2)->default(0);
            $table->decimal('budget_max', 12, 2)->default(0);
            $table->string('duration')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['active', 'closed', 'draft'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('applications_count')->default(0);
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('cover_letter');
            $table->decimal('proposal_amount', 12, 2);
            $table->string('estimated_duration');
            $table->enum('status', ['pending', 'hired', 'rejected', 'withdrawn'])->default('pending');
            $table->timestamps();

            $table->unique(['job_id', 'user_id']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('jobs');
    }
};
