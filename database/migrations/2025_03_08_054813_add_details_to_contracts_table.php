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
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('purpose')->nullable()->after('vendor_id');
            $table->string('approved_by')->nullable()->after('purpose');
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending')->after('fraud_notes');
            $table->string('actioned_by')->nullable()->after('admin_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['purpose', 'approved_by', 'admin_status']);
        });
    }
};
