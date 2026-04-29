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
             $table->string('step');              // Mail 1, Mail 2
            $table->string('subject');
            $table->longText('message');        // HTML message

            $table->integer('gap_days')->default(0); // delay system

            $table->string('variant')->nullable();   // A, B, C
            $table->string('type')->nullable();      // customer type

            $table->string('hero_image')->nullable(); // image

            // optional message customization
            $table->text('whatsapp_link')->nullable();
            $table->text('telegram_link')->nullable();
            $table->text('business_link')->nullable();
            $table->timestamps();
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
