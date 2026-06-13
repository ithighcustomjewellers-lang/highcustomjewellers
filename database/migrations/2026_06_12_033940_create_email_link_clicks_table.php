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
        Schema::create('email_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_log_id')
                ->constrained()
                ->cascadeOnDelete()
                ->index();
            $table->string('platform_name')->index();
            $table->text('destination_url');
            $table->string('click_token', 100)->unique();
            $table->timestamp('clicked_at')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Additional indexes for performance
            $table->index(['campaign_log_id', 'platform_name']);
            $table->index('clicked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_link_clicks');
    }
};
