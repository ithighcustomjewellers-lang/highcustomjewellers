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
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('step');
            $table->integer('gap_days');
            $table->string('variant')->nullable();
            $table->enum('type', ['B2B', 'B2C']);
            $table->string('subject');
            $table->string('existing_company_logo')->nullable();
            $table->string('image_type')->nullable();
            $table->string('logo_position')->nullable();
            $table->longText('message');
            $table->string('hero_image')->nullable();
            $table->string('attachments_image')->nullable();
            $table->string('whatsapp_link')->nullable();
            $table->string('telegram_link')->nullable();
            $table->string('business_link')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sequences');
    }
};
