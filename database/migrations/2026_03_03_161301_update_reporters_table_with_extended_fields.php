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
        Schema::table('reporters', function (Blueprint $table) {
            $table->json('social_links')->nullable()->after('photo_path');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->after('beat');
            $table->decimal('revenue_share', 5, 2)->default(0)->after('category_id');
            $table->decimal('rating_average', 3, 2)->default(0)->after('revenue_share');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reporters', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['social_links', 'category_id', 'revenue_share', 'rating_average']);
        });
    }
};
