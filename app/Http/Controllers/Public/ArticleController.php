<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;

class ArticleController extends Controller
{
    public function show($slug, Request $request)
    {
        $article = \Illuminate\Support\Facades\Cache::remember("article_{$slug}", 3600, function() use ($slug) {
            return Article::with(['category', 'user', 'tags', 'media'])->where('slug', $slug)
                ->where('status', 'published')
                ->where('moderation_status', 'approved')
                ->where('published_at', '<=', now())
                ->firstOrFail();
        });

        $article->increment('views_count');

        // Capture Analytics
        \App\Models\Analytic::create([
            'article_id' => $article->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'device_type' => $this->getDeviceType($request->userAgent()),
            'visited_at' => now(),
        ]);

        // Generate Captcha for Comments
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        session(['comment_captcha' => $num1 + $num2]);
            
        return view('articles.show', compact('article', 'num1', 'num2'));
    }

    private function getDeviceType($userAgent)
    {
        $userAgent = strtolower($userAgent);
        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android') || str_contains($userAgent, 'iphone')) {
            return 'mobile';
        }
        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'tablet';
        }
        return 'desktop';
    }
}
