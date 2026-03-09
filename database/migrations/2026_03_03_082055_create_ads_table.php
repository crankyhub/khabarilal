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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['image', 'script'])->default('image');
            $table->text('content'); // URL for image, code for script
            $table->string('link_url')->nullable(); // For image ads
            $table->string('position')->default('sidebar'); // sidebar, in_feed, popup
            $table->boolean('is_active')->default(true);
            $table->integer('clicks_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
