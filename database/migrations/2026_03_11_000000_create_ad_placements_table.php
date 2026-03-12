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
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained()->onDelete('cascade');
            $table->string('position'); // top_banner, sidebar, in_feed, etc.
            $table->enum('type', ['image', 'script'])->default('image');
            $table->string('image_path')->nullable();
            $table->text('content')->nullable(); // URL for image, code for script
            $table->string('link_url')->nullable();
            $table->timestamps();
        });

        // Also clean up the ads table from fields that are now in placements
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn(['content', 'link_url', 'position', 'image_path', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('type')->default('image')->after('title');
            $table->text('content')->nullable()->after('type');
            $table->string('link_url')->nullable()->after('content');
            $table->string('position')->default('sidebar')->after('link_url');
            $table->string('image_path')->nullable()->after('position');
        });

        Schema::dropIfExists('ad_placements');
    }
};
