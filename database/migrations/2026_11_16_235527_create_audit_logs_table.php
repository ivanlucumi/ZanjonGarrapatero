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
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name', 120);
            $table->string('user_role', 40);
            $table->string('action', 60)->index(); // created, updated, inactivated, restored, toggled, role_changed
            $table->string('entity_type', 80)->index(); // Publication, Activity, MediaItem, Survey, User
            $table->unsignedBigInteger('entity_id')->nullable()->index();
            $table->string('entity_title')->nullable();
            $table->string('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('properties')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
