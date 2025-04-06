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
        // Drop the existing unique constraint on email
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });

        // Add a composite unique index on email and deleted_at
        Schema::table('users', function (Blueprint $table) {
            $table->unique(['email', 'deleted_at'], 'users_email_deleted_at_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_deleted_at_unique');
            $table->unique('email');
        });
    }
};
