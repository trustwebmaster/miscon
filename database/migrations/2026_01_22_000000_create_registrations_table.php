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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->enum('type', ['student', 'alumni']);
            $table->string('full_name');
            $table->string('university');
            $table->string('phone');
            $table->string('id_number')->unique(); // Reg number for students, National ID for alumni
            $table->enum('gender', ['male', 'female']);
            $table->string('level'); // Academic level for students, graduation year for alumni
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('payment_method')->nullable(); // ecocash, innbucks
            $table->string('payment_phone')->nullable();
            $table->string('paynow_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Indexes for faster queries
            $table->index('type');
            $table->index('payment_status');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
