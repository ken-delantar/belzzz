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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->string('truck_type');
            $table->string('route_from');
            $table->string('route_to');
            $table->integer('total_capacity');
            $table->integer('available_capacity');
            $table->enum('status', ['ready', 'maintenance'])->default('ready');
            $table->timestamp('last_updated')->nullable();
            $table->text('available_parts')->nullable();
            $table->text('maintenance_record')->nullable();
            $table->text('fuel_consumption')->nullable();
            $table->tinyInteger('isDeleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
