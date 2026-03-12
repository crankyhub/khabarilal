<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Article;

$breaking = Article::where('is_breaking', true)->get();
echo "Total breaking: " . $breaking->count() . "\n";
foreach ($breaking as $a) {
    echo "ID: {$a->id}, Title: {$a->title}, Status: {$a->status}, Moderation: {$a->moderation_status}, Breaking: {$a->is_breaking}, Published At: " . ($a->published_at ? $a->published_at->toDateTimeString() : "NULL") . "\n";
}
