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
        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->string('name')->nullable();
            $table->text('request');
            $table->enum('status', ['pending', 'prayed', 'archived'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('prayed_at')->nullable();
            $table->timestamps();
            
            $table->index('phone');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_requests');
    }
};
