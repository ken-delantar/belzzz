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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('proposal_title');
            $table->string('vendor_name')->nullable(); // Add this
            $table->string('email')->nullable(); // Add this
            $table->string('product_service_type')->nullable();
            $table->text('purpose')->nullable();
            $table->string('pricing')->nullable();
            $table->string('delivery_timeline')->nullable();
            $table->date('valid_until')->nullable();
            $table->float('ai_score')->nullable();
            $table->boolean('is_fraud')->nullable(); // Add this
            $table->text('notes')->nullable(); // Add this (changed from JSON to text)
            $table->string('admin_status')->nullable();
            $table->string('actioned_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
