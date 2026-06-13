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
            if (!Schema::hasColumn('campaign_logs', 'tracking_token')) {
                $table->string('tracking_token', 100)->nullable()->unique()->after('sequence_id');
            }
            if (!Schema::hasColumn('campaign_logs', 'seen_at')) {
                $table->timestamp('seen_at')->nullable()->after('sent_at');
            }
            if (!Schema::hasColumn('campaign_logs', 'ip_address')) {
                $table->ipAddress('ip_address')->nullable()->after('seen_at');
            }
            if (!Schema::hasColumn('campaign_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('campaign_logs', 'total_clicks')) {
                $table->integer('total_clicks')->default(0)->after('user_agent');
            }

            // Add indexes
            $table->index('tracking_token');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign_logs', function (Blueprint $table) {
            $table->dropColumn(['tracking_token', 'seen_at', 'ip_address', 'user_agent', 'total_clicks']);
        });
    }
};
