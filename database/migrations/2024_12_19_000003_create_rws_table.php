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
        Schema::create('rws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dusun_id')->constrained()->cascadeOnDelete();
            $table->string('number', 3);
            $table->string('name')->nullable();
            $table->foreignId('ketua_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Unique constraint per village
            $table->unique(['desa_id', 'number']);
            $table->index(['desa_id', 'dusun_id']);
            $table->index('ketua_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rws');
    }
};