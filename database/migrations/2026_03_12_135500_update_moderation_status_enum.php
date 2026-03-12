<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL specific way to update ENUM
        DB::statement("ALTER TABLE articles MODIFY COLUMN moderation_status ENUM('pending', 'approved', 'rejected', 'unpublished') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This might fail if there are 'unpublished' values in the table.
        // It's better to update them to 'pending' or 'approved' first if needed.
        DB::table('articles')->where('moderation_status', 'unpublished')->update(['moderation_status' => 'approved']);
        DB::statement("ALTER TABLE articles MODIFY COLUMN moderation_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
