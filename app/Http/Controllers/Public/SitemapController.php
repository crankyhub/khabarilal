<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $articles = Article::where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->get();

        $categories = Category::all();

        $content = view('public.sitemap', compact('articles', 'categories'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
