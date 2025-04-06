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
        Schema::create('vehicle_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->string('driver_name');
            $table->string('route_from');
            $table->string('route_to');
            $table->integer('total_capacity');
            $table->integer('available_capacity');
            $table->enum('status', ['ready', 'maintenance'])->default('ready');
            $table->timestamp('last_updated')->nullable();
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_inventories');
    }
};
