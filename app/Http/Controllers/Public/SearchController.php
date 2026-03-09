<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        $articles = Article::with(['category', 'user'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('body', 'LIKE', "%{$query}%")
                  ->orWhereHas('tags', function($t) use ($query) {
                      $t->where('name', 'LIKE', "%{$query}%");
                  });
            })
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('welcome', [
            'articles' => $articles,
            'currentCategory' => (object)['name' => "Results for: '{$query}'"]
        ]);
    }
}
