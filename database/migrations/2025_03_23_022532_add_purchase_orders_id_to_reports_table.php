<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_orders_id')->nullable()->after('report_by');
            $table->foreign('purchase_orders_id')->references('id')->on('purchase_orders')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['purchase_orders_id']);
            $table->dropColumn('purchase_orders_id');
        });
    }
};
