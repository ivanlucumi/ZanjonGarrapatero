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
        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 150);
            $table->string('phone', 40)->nullable();
            $table->string('territory', 120);
            $table->text('message');
            $table->string('status', 30)->default('pendiente'); // pendiente, en_tramite, atendido, archivado
            $table->text('admin_notes')->nullable();
            $table->foreignId('attended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('attended_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
