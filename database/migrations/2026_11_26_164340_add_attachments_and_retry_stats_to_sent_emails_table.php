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
        Schema::table('sent_emails', function (Blueprint $table): void {
            $table->json('attachments_meta')->nullable()->after('recipient_emails');
            $table->unsignedInteger('failed_count')->default(0)->after('recipients_count');
            $table->json('failed_recipients')->nullable()->after('failed_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sent_emails', function (Blueprint $table): void {
            $table->dropColumn(['attachments_meta', 'failed_count', 'failed_recipients']);
        });
    }
};
