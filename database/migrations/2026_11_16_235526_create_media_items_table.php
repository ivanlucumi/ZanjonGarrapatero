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
        Schema::create('media_items', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type', 20)->default('image')->index(); // 'image' or 'video'
            $table->string('category', 80)->default('Territorio')->index();
            $table->string('caption')->nullable();
            $table->string('file_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('duration', 30)->nullable(); // e.g. '12 min'
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true)->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
