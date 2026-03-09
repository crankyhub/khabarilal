@extends('layouts.admin')

@section('header', 'Dashboard Overview')

@section('content')
<div class="dashboard-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card stat-card">
        <h3 style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Articles</h3>
        <p style="font-size: 2rem; font-weight: 700;">{{ $stats['articles'] }}</p>
    </div>
    <div class="card stat-card">
        <h3 style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">Categories</h3>
        <p style="font-size: 2rem; font-weight: 700;">{{ $stats['categories'] }}</p>
    </div>
    <div class="card stat-card">
        <h3 style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem;">Total Views</h3>
        <p style="font-size: 2rem; font-weight: 700; color: #10b981;">{{ number_format($stats['views']) }}</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <div class="card">
        <h3>Traffic Trends (Last 7 Days)</h3>
        <div style="height: 300px; margin-top: 2rem;">
            <canvas id="viewsChart"></canvas>
        </div>
    </div>
    
    <div class="card">
        <h3>Device Distribution</h3>
        <div style="height: 300px; margin-top: 2rem;">
            <canvas id="deviceChart"></canvas>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div class="card">
        <h3>Top Performing Articles</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.8rem;">
                    <th style="padding: 1rem 0;">ARTICLE TITLE</th>
                    <th style="padding: 1rem 0; text-align: right;">VIEWS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topArticles as $article)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem 0; font-size: 0.9rem;">{{ Str::limit($article->title, 50) }}</td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 700; color: var(--accent);">{{ number_format($article->views_count) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>Top Reporters</h3>
        <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.8rem;">
                    <th style="padding: 1rem 0;">REPORTER</th>
                    <th style="padding: 1rem 0; text-align: right;">TOTAL VIEWS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topReporters as $reporter)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 1rem 0; font-size: 0.9rem; display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 24px; height: 24px; background: #334155; border-radius: 50%; overflow: hidden;">
                             @if($reporter->photo_path)
                                <img src="{{ asset('storage/' . $reporter->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                             @endif
                        </div>
                        {{ $reporter->user->name }}
                    </td>
                    <td style="padding: 1rem 0; text-align: right; font-weight: 700; color: #10b981;">{{ number_format($reporter->total_views) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 2rem;">
    <div class="card">
        <h3>Quick Actions</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary" style="text-decoration: none; text-align: center; font-size: 0.9rem; padding: 1rem;">+ Create News</a>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline" style="text-decoration: none; text-align: center; font-size: 0.9rem; padding: 1rem; border: 1px solid var(--border); color: var(--text-secondary);">🏷️ Manage Tags</a>
            <a href="{{ route('admin.reporters.index') }}" class="btn btn-outline" style="text-decoration: none; text-align: center; font-size: 0.9rem; padding: 1rem; border: 1px solid var(--border); color: var(--text-secondary);">👥 Reporters</a>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Views Chart
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($viewsData->pluck('date')) !!},
            datasets: [{
                label: 'Daily Views',
                data: {!! json_encode($viewsData->pluck('count')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Device Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deviceData->pluck('device_type')) !!},
            datasets: [{
                data: {!! json_encode($deviceData->pluck('count')) !!},
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#94a3b8', boxWidth: 12 } }
            }
        }
    });
</script>
@endsection
