<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rating;
use App\Models\Article;

class RatingController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            [
                'article_id' => $article->id,
                'ip_address' => $request->ip(),
            ],
            [
                'stars' => $request->stars,
            ]
        );

        return back()->with('success', 'Thank you for rating!');
    }
}
