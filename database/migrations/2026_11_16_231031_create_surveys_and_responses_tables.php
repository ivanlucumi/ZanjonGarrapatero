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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category', 80)->default('Censo Territorial');
            $table->string('target_vereda', 100)->nullable(); // null means all veredas
            $table->string('status', 20)->default('active'); // draft, active, closed
            $table->boolean('is_published')->default(true);
            $table->json('questions')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('answers');
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('submitted'); // submitted, verified, reviewed
            $table->timestamps();

            $table->unique(['survey_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('surveys');
    }
};
