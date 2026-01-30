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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('donor_name')->nullable()->default('Anonymous');
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->text('message')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_status')->default('pending'); // pending, processing, completed, failed
            $table->string('payment_method')->nullable(); // ecocash, onemoney, innbucks
            $table->string('payment_phone')->nullable();
            $table->string('paynow_reference')->nullable();
            $table->string('paynow_poll_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('payment_status');
            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
