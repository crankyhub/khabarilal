@extends('layouts.admin')

@section('header', 'My Reporter Dashboard')

@section('content')
<div class="reporter-welcome" style="margin-bottom: 2rem;">
    <h3>Namaste, {{ auth()->user()->name }}! 🖋️</h3>
    <p style="color: var(--text-secondary);">Here is your performance overview and earnings report.</p>
</div>

<div class="dashboard-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card stat-card" style="border-left: 4px solid var(--accent);">
        <h3 style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Estimated Earnings</h3>
        <p style="font-size: 1.8rem; font-weight: 800; color: var(--brand-black);">${{ number_format($stats['earnings'], 2) }}</p>
        <div style="font-size: 0.75rem; color: #10b981; margin-top: 0.5rem;">Based on {{ $reporter->revenue_share }}% revenue share</div>
    </div>
    
    <div class="card stat-card">
        <h3 style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Total Impact</h3>
        <p style="font-size: 1.8rem; font-weight: 800;">{{ number_format($stats['total_views']) }}</p>
        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Total Article Views</div>
    </div>

    <div class="card stat-card">
        <h3 style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Published Works</h3>
        <p style="font-size: 1.8rem; font-weight: 800;">{{ $stats['approved_articles'] }}</p>
        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Out of {{ $stats['total_articles'] }} submissions</div>
    </div>

    <div class="card stat-card">
        <h3 style="color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Pending Review</h3>
        <p style="font-size: 1.8rem; font-weight: 800; color: #f59e0b;">{{ $stats['pending_articles'] }}</p>
        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Awaiting Editor Approval</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3>My Recent Submissions</h3>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem;">+ Write New</a>
        </div>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.8rem;">
                    <th style="padding: 1rem 0;">TITLE</th>
                    <th style="padding: 1rem 0;">MODERATION</th>
                    <th style="padding: 1rem 0; text-align: right;">VIEWS</th>
                    <th style="padding: 1rem 0; text-align: right;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentArticles as $article)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem 0;">
                        <div style="font-weight: 600; font-size: 0.9rem;">{{ Str::limit($article->title, 60) }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $article->category->name }} • {{ $article->created_at->diffForHumans() }}</div>
                    </td>
                    <td style="padding: 1rem 0;">
                        @if($article->moderation_status === 'approved')
                            <span style="color: #10b981; font-weight: 700; font-size: 0.75rem;">✓ Approved</span>
                        @elseif($article->moderation_status === 'rejected')
                            <span style="color: var(--brand-red); font-weight: 700; font-size: 0.75rem;" title="{{ $article->rejection_reason }}">✕ Rejected</span>
                        @else
                            <span style="color: #f59e0b; font-weight: 700; font-size: 0.75rem;">⏳ Pending</span>
                        @endif
                    </td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 700;">{{ number_format($article->views_count) }}</td>
                    <td style="padding: 1rem 0; text-align: right;">
                        <a href="{{ route('admin.articles.edit', $article) }}" style="color: var(--accent); text-decoration: none; font-size: 0.85rem;">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 2rem; text-align: center; color: var(--text-secondary);">No articles submitted yet. Start writing!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card" style="background: var(--brand-black); color: #fff; border: none;">
        <h3>My Earnings Breakdown</h3>
        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 0.5rem;">How your revenue is calculated.</p>
        
        <div style="margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #334155;">
                <span style="color: #94a3b8;">Base CPM</span>
                <span style="font-weight: 700;">$10.00</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #334155;">
                <span style="color: #94a3b8;">Total Impressions</span>
                <span style="font-weight: 700;">{{ number_format($stats['total_views']) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #334155;">
                <span style="color: #94a3b8;">Platform Revenue</span>
                <span style="font-weight: 700;">${{ number_format(($stats['total_views'] / 1000) * 10, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 2rem;">
                <span style="font-weight: 700; color: var(--brand-red);">Your Share ({{ $reporter->revenue_share }}%)</span>
                <span style="font-size: 1.5rem; font-weight: 800; color: #fff;">${{ number_format($stats['earnings'], 2) }}</span>
            </div>
        </div>
        
        <div style="margin-top: 3rem; background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 1rem; font-size: 0.85rem;">
            <p style="margin: 0;">Earnings are updated every 24 hours based on unique article views. Contact finance for payout schedule.</p>
        </div>
    </div>
</div>
@endsection
