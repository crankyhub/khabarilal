<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $box) {
            $box->string('meta_title')->nullable()->after('summary');
            $box->text('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $box) {
            $box->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
