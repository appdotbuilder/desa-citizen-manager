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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->enum('category', ['announcement', 'news', 'event', 'regulation'])->default('news');
            $table->enum('status', ['draft', 'pending_approval', 'published', 'archived'])->default('draft');
            $table->boolean('is_pinned')->default(false);
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->json('tags')->nullable();
            $table->integer('view_count')->default(0);
            $table->timestamps();
            
            $table->index(['desa_id', 'status']);
            $table->index(['desa_id', 'category']);
            $table->index(['desa_id', 'published_at']);
            $table->index(['desa_id', 'is_pinned']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};