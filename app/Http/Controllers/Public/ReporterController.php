<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reporter;
use App\Models\Article;

class ReporterController extends Controller
{
    public function show($id)
    {
        $reporter = Reporter::with('user')->findOrFail($id);
        
        $articles = Article::with(['category', 'user'])
            ->where('user_id', $reporter->user_id)
            ->where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);

        return view('reporters.show', compact('reporter', 'articles'));
    }
}
