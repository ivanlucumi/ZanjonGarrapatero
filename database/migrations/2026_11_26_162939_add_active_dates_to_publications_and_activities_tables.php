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
        Schema::table('publications', function (Blueprint $table): void {
            $table->timestamp('active_from')->nullable()->after('published_at');
            $table->timestamp('active_until')->nullable()->after('active_from');
        });

        Schema::table('activities', function (Blueprint $table): void {
            $table->timestamp('active_from')->nullable()->after('event_date');
            $table->timestamp('active_until')->nullable()->after('active_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table): void {
            $table->dropColumn(['active_from', 'active_until']);
        });

        Schema::table('activities', function (Blueprint $table): void {
            $table->dropColumn(['active_from', 'active_until']);
        });
    }
};
