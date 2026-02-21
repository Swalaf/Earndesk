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
        // Growth Listings (Backlinks, Influencers, Newsletters, Leads)
        Schema::create('growth_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // backlinks, influencer, newsletter, leads
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('delivery_days')->default(1);
            
            // Type-specific fields stored as JSON
            $table->json('specs')->nullable(); // Type-specific specifications
            
            // Status
            $table->enum('status', ['draft', 'pending', 'active', 'paused', 'rejected', 'deleted'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index(['type', 'status']);
            $table->index('user_id');
        });

        // Growth Orders (escrow-based)
        Schema::create('growth_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('growth_listings')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_commission', 10, 2)->default(0);
            $table->decimal('seller_payout', 10, 2)->default(0);
            $table->decimal('escrow_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            
            $table->enum('status', [
                'pending',      // Waiting for payment
                'paid',        // Paid, in escrow
                'in_progress', // Working
                'delivered',   // Proof submitted
                'revision',    // Revision requested
                'completed',   // Approved
                'cancelled',   // Cancelled
                'disputed',    // Dispute
                'refunded'     // Refunded
            ])->default('pending');
            
            // Proof submission
            $table->text('proof_data')->nullable(); // JSON of proof details
            $table->text('proof_notes')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });

        // Growth Categories
        Schema::create('growth_categories', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // backlinks, influencer, newsletter, leads
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_categories');
        Schema::dropIfExists('growth_orders');
        Schema::dropIfExists('growth_listings');
    }
};
