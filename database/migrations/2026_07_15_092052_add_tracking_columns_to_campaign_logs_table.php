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
        Schema::table('campaign_logs', function (Blueprint $table) {
            $table->ipAddress('sender_ip')->nullable()->after('user_id');
            $table->string('open_token', 100)->unique()->nullable()->after('id');
            $table->timestamp('opened_at')->nullable()->after('seen_at');
            $table->ipAddress('opened_ip')->nullable();
            $table->text('opened_user_agent')->nullable();
            $table->boolean('is_human_open')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_logs', function (Blueprint $table) {
             $table->dropColumn([
                'sender_ip',
                'open_token',
                'opened_at',
                'opened_ip',
                'opened_user_agent',
                'is_human_open'
            ]);
        });
    }
};
