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
        Schema::create('compliance_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_compliance_id')->constrained('vendor_compliance')->onDelete('cascade');
            $table->string('document_path');
            $table->string('file_name');
            $table->integer('file_size');
            $table->string('file_type');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_documents');
    }
};
