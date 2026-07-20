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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->enum('document_type', ['surat', 'notulen', 'dokumentasi']);
            $table->string('file_name');
            $table->string('file_url');
            $table->timestamps();

            // Database Index
            $table->index('document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
