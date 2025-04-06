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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicle_inventories')->onDelete('cascade')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_tech')->nullable()->constrained('users')->onDelete('set null');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_priority')->default(false); // Use boolean instead of tinyInteger
            $table->date('maintenance_date');
            $table->string('maintenance_type')->nullable()->default('general service'); // Nullable with default
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
