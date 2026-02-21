<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Professional Services Listings
        Schema::create('service_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->foreignId('service_category_id')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('delivery_days');
            $table->integer('revisions_included')->default(1);
            $table->json('portfolio_images')->nullable();
            $table->json('add_ons')->nullable(); // [{name, price, description}]
            $table->enum('status', ['draft', 'active', 'paused', 'rejected'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Service Categories
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Service Orders
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('service_listings')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 15, 2); // Total price
            $table->decimal('platform_fee', 15, 2)->default(0);
            $table->decimal('escrow_amount', 15, 2)->default(0);
            $table->enum('status', [
                'pending',      // Order placed, awaiting payment
                'paid',         // Payment received, in escrow
                'in_progress',  // Seller working on order
                'delivered',    // Seller delivered, awaiting approval
                'completed',    // Buyer approved, funds released
                'disputed',     // Buyer opened dispute
                'cancelled',    // Order cancelled
                'refunded'      // Refunded to buyer
            ])->default('pending');
            $table->text('requirements')->nullable(); // Buyer's requirements
            $table->text('delivery_notes')->nullable(); // Seller's delivery notes
            $table->json('delivered_files')->nullable();
            $table->integer('revision_count')->default(0);
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Service Reviews
        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('service_orders')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->enum('type', ['buyer_to_seller', 'seller_to_buyer']);
            $table->timestamps();
        });

        // Service Messages (for order communication)
        Schema::create('service_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('service_orders')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->json('attachments')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Service Add-ons (purchased)
        Schema::create('service_order_add_ons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('service_orders')->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->text('description')->nullable();
            $table->boolean('is_included')->default(false);
            $table->timestamps();
        });

        // Growth Marketplace Listings
        Schema::create('growth_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['backlink', 'influencer', 'newsletter', 'lead']);
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 15, 2);
            $table->integer('delivery_days')->default(1);
            $table->integer('min_quantity')->default(1);
            
            // Type-specific fields (JSON for flexibility)
            $table->json('specs'); // Type-specific specifications
            
            $table->enum('status', ['draft', 'active', 'paused', 'rejected'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        // Growth Orders
        Schema::create('growth_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('growth_listings')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('platform_fee', 15, 2)->default(0);
            $table->decimal('escrow_amount', 15, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->enum('status', [
                'pending', 'paid', 'in_progress', 'delivered', 
                'completed', 'disputed', 'cancelled', 'refunded'
            ])->default('pending');
            $table->text('proof')->nullable(); // Proof of delivery
            $table->text('proof_notes')->nullable();
            $table->json('proof_files')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Service Provider Profiles (for Directory)
        Schema::create('service_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->boolean('is_available')->default(true);
            $table->boolean('offers_services')->default(false);
            $table->string('headline')->nullable();
            $table->text('bio')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->json('skills')->nullable(); // Array of skill tags
            $table->json('portfolio_links')->nullable(); // Array of URLs
            $table->decimal('rating_average', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->integer('order_completed')->default(0);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
        });

        // Disputes
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->morphs('disputable'); // Can be service_order or growth_order
            $table->foreignId('raiser_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('responder_id')->constrained('users')->onDelete('cascade');
            $table->text('reason');
            $table->text('resolution_notes')->nullable();
            $table->enum('status', ['open', 'under_review', 'resolved', 'closed'])->default('open');
            $table->enum('resolution', ['buyer_wins', 'seller_wins', 'refund', 'split'])->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        // Add commission settings
        Schema::table('system_settings', function (Blueprint $table) {
            $table->decimal('service_commission_rate', 5, 2)->default(10)->after('key');
            $table->decimal('growth_commission_rate', 5, 2)->default(10)->after('key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('disputes');
        Schema::dropIfExists('service_profiles');
        Schema::dropIfExists('growth_orders');
        Schema::dropIfExists('growth_listings');
        Schema::dropIfExists('service_order_add_ons');
        Schema::dropIfExists('service_messages');
        Schema::dropIfExists('service_reviews');
        Schema::dropIfExists('service_orders');
        Schema::dropIfExists('service_categories');
        Schema::dropIfExists('service_listings');
    }
};
