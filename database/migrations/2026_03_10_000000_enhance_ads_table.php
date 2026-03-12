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
        Schema::table('ads', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('content');
            $table->unsignedBigInteger('category_id')->nullable()->after('image_path');
            $table->unsignedBigInteger('article_id')->nullable()->after('category_id');
            $table->integer('limit_impressions')->default(0)->after('article_id');
            $table->integer('current_impressions')->default(0)->after('limit_impressions');
            $table->integer('limit_clicks')->default(0)->after('current_impressions');
            $table->integer('current_clicks')->default(0)->after('limit_clicks');
            $table->timestamp('start_date')->nullable()->after('current_clicks');
            $table->timestamp('end_date')->nullable()->after('start_date');
            $table->decimal('total_budget', 10, 2)->default(0.00)->after('end_date');
            $table->decimal('remaining_budget', 10, 2)->default(0.00)->after('total_budget');
            $table->decimal('cost_per_impression', 8, 2)->default(0.00)->after('remaining_budget');
            $table->decimal('cost_per_click', 8, 2)->default(0.00)->after('cost_per_impression');
            $table->enum('status', ['active', 'paused', 'exhausted', 'expired'])->default('active')->after('is_active');

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['article_id']);
            $table->dropColumn([
                'image_path', 'category_id', 'article_id', 'limit_impressions', 
                'current_impressions', 'limit_clicks', 'current_clicks', 
                'start_date', 'end_date', 'total_budget', 'remaining_budget', 
                'cost_per_impression', 'cost_per_click', 'status'
            ]);
        });
    }
};
