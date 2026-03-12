<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(10)
            ->get();
            
        return view('welcome', compact('articles'));
    }

    public function category($slug)
    {
        $currentCategory = Category::where('slug', $slug)->firstOrFail();
        $articles = Article::with(['category', 'user'])
            ->where('category_id', $currentCategory->id)
            ->where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);
            
        return view('welcome', compact('articles', 'currentCategory'));
    }

    public function tag($slug)
    {
        $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
        $articles = Article::with(['category', 'user'])
            ->whereHas('tags', function($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);
            
        return view('welcome', [
            'articles' => $articles,
            'currentCategory' => (object)['name' => 'Tagged: ' . $tag->name]
        ]);
    }

    public function swipe()
    {
        $articles = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(20)
            ->get();
            
        return view('swipe', compact('articles'));
    }
}
