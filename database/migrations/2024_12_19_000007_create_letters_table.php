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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('letter_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('citizen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('rt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rw_id')->constrained()->cascadeOnDelete();
            $table->string('letter_number')->nullable();
            $table->string('subject');
            $table->json('form_data')->nullable();
            $table->text('purpose');
            $table->enum('status', [
                'draft', 
                'rt_approved', 
                'rw_approved', 
                'admin_process', 
                'kepala_desa_approved', 
                'selesai',
                'rejected'
            ])->default('draft');
            $table->enum('submission_type', ['online', 'manual']);
            $table->foreignId('created_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance and tenant isolation
            $table->index(['desa_id', 'status']);
            $table->index(['desa_id', 'citizen_id']);
            $table->index(['desa_id', 'rt_id']);
            $table->index(['desa_id', 'rw_id']);
            $table->index(['desa_id', 'created_at']);
            $table->index('letter_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};