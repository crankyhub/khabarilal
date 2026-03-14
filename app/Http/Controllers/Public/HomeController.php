<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(10);

        if ($request->ajax()) {
            $view = '';
            foreach ($articles as $article) {
                $view .= view('partials.article-item', compact('article'))->render();
            }
            return response()->json([
                'html' => $view,
                'hasMore' => $articles->hasMorePages()
            ]);
        }
            
        return view('welcome', compact('articles'));
    }

    public function category(Request $request, $slug)
    {
        $currentCategory = Category::where('slug', $slug)->firstOrFail();
        $articles = Article::with(['category', 'user'])
            ->where('category_id', $currentCategory->id)
            ->where('status', 'published')
            ->where('moderation_status', 'approved')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(12);

        if ($request->ajax()) {
            $view = '';
            foreach ($articles as $article) {
                $view .= view('partials.article-item', compact('article'))->render();
            }
            return response()->json([
                'html' => $view,
                'hasMore' => $articles->hasMorePages()
            ]);
        }
            
        return view('welcome', compact('articles', 'currentCategory'));
    }

    public function tag(Request $request, $slug)
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

        if ($request->ajax()) {
            $view = '';
            foreach ($articles as $article) {
                $view .= view('partials.article-item', compact('article'))->render();
            }
            return response()->json([
                'html' => $view,
                'hasMore' => $articles->hasMorePages()
            ]);
        }
            
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
            ->take(50)
            ->get();
            
        return view('swipe', compact('articles'));
    }
}
