<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // If table already exists (created by earlier combined migration), skip to avoid duplicate creation errors
        if (Schema::hasTable('wallet_ledgers')) {
            return;
        }

        // Create wallet_ledgers table
        Schema::create('wallet_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('user_id');
            $table->string('type', 50);
            $table->decimal('amount', 15, 2);
            $table->decimal('withdrawable_before', 15, 2)->default(0);
            $table->decimal('withdrawable_after', 15, 2)->default(0);
            $table->decimal('promo_credit_before', 15, 2)->default(0);
            $table->decimal('promo_credit_after', 15, 2)->default(0);
            $table->string('currency', 10)->default('NGN');
            $table->string('status', 20)->default('completed');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['wallet_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('wallet_ledgers');
    }
};
