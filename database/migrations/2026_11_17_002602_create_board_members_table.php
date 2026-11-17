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
        Schema::create('board_members', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('role_title', 120);
            $table->string('committee', 100)->default('Junta de Gobierno'); // Junta de Gobierno, Guardia Cimarrona, Comité Ambiental, etc.
            $table->string('vereda', 100)->nullable();
            $table->text('bio')->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('photo_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_members');
    }
};
