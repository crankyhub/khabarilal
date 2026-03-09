<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Category;
use App\Models\Reporter;
use App\Models\User;
use App\Models\Analytic;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === User::ROLE_REPORTER || $user->role === User::ROLE_GUEST) {
            return $this->reporterDashboard($user);
        }

        return $this->adminDashboard();
    }

    protected function adminDashboard()
    {
        $stats = [
            'articles' => Article::count(),
            'categories' => Category::count(),
            'reporters' => Reporter::count(),
            'views' => Article::sum('views_count'),
        ];

        // Last 7 days views
        $viewsData = Analytic::select(
            DB::raw('DATE(visited_at) as date'),
            DB::raw('count(*) as count')
        )
        ->where('visited_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Device breakdown
        $deviceData = Analytic::select(
            'device_type',
            DB::raw('count(*) as count')
        )
        ->groupBy('device_type')
        ->get();

        // Top 5 articles
        $topArticles = Article::select('title', 'views_count')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Top Reporters by views
        $topReporters = Reporter::with('user')
            ->join('articles', 'reporters.user_id', '=', 'articles.user_id')
            ->select('reporters.*', DB::raw('SUM(articles.views_count) as total_views'))
            ->groupBy('reporters.id', 'reporters.user_id', 'reporters.bio', 'reporters.photo_path', 'reporters.beat', 'reporters.created_at', 'reporters.updated_at')
            ->orderBy('total_views', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'viewsData', 'deviceData', 'topArticles', 'topReporters'));
    }

    protected function reporterDashboard($user)
    {
        $reporter = $user->reporter;
        
        if (!$reporter) {
            // Fallback or redirection if profile is missing
            return $this->adminDashboard();
        }

        $articles = Article::where('user_id', $user->id)->get();

        
        $stats = [
            'total_articles' => $articles->count(),
            'approved_articles' => $articles->where('moderation_status', 'approved')->count(),
            'pending_articles' => $articles->where('moderation_status', 'pending')->count(),
            'rejected_articles' => $articles->where('moderation_status', 'rejected')->count(),
            'total_views' => $articles->sum('views_count'),
        ];

        // Revenue Calculation: Assume $0.01 per view (Configurable later)
        $cpm_rate = 10; // $10 per 1000 views as a base
        $total_revenue = ($stats['total_views'] / 1000) * $cpm_rate;
        $revenue_share = $reporter->revenue_share ?? 0;
        $stats['earnings'] = $total_revenue * ($revenue_share / 100);


        // Recent Activity
        $recentArticles = Article::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.reporter_dashboard', compact('stats', 'recentArticles', 'reporter'));
    }
}

