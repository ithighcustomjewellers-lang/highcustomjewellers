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
        Schema::create('campaign_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lead_id')->nullable();

            $table->unsignedBigInteger('sequence_id');

            $table->timestamp('sent_at')->nullable();

            $table->string('status')->default('pending');

            $table->string('variant', 15)->nullable();

            $table->timestamps();

            // Optional Foreign Keys
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->onDelete('cascade');

            $table->foreign('sequence_id')
                ->references('id')
                ->on('sequences')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_logs');
    }
};
