<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Analytic;
use App\Models\Article;

class LogArticleView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for successful article detail pages
        if ($request->routeIs('article.show') && $response->getStatusCode() === 200) {
            $slug = $request->route('slug');
            $article = Article::where('slug', $slug)->first();

            if ($article) {
                $userAgent = $request->userAgent();
                $deviceType = 'desktop';

                if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobi))/i', $userAgent)) {
                    $deviceType = 'tablet';
                } elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $userAgent)) {
                    $deviceType = 'mobile';
                }

                Analytic::create([
                    'article_id' => $article->id,
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'referrer' => $request->header('referer'),
                    'device_type' => $deviceType,
                    'visited_at' => now(),
                ]);
            }
        }

        return $response;
    }
}
