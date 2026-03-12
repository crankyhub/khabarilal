@extends('layouts.admin')

@section('header', 'News Articles & Moderation')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3>Content Pipeline</h3>
            <p style="font-size: 0.9rem; color: var(--text-secondary);">Approve or reject articles from reporters.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">+ Write New Article</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.9rem;">
                <th style="padding: 1rem;">ARTICLE & REPORTER</th>
                <th style="padding: 1rem;">CATEGORY</th>
                <th style="padding: 1rem;">MODERATION</th>
                <th style="padding: 1rem;">PUB STATUS</th>
                <th style="padding: 1rem;">DATE</th>
                <th style="padding: 1rem; text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 50px; height: 50px; background: #334155; border-radius: 0.5rem; overflow: hidden; flex-shrink: 0;">
                            @if($article->image_path)
                                <img src="{{ asset('storage/' . $article->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--brand-black);">{{ $article->title }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">By <strong>{{ $article->user?->name ?? 'Unknown' }}</strong></div>
                            @if($article->is_breaking)
                                <span style="display: inline-block; background: #be1e2d; color: #fff; font-size: 0.65rem; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 800; margin-top: 0.25rem;">BREAKING</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="padding: 1rem;">
                    <span style="padding: 0.25rem 0.5rem; background: rgba(59, 130, 246, 0.1); color: var(--accent); border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                        {{ $article->category?->name ?? 'Uncategorized' }}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    @if($article->moderation_status === 'approved')
                        <span style="color: #10b981; font-weight: 700; font-size: 0.85rem;">✓ Approved</span>
                    @elseif($article->moderation_status === 'rejected')
                        <span style="color: var(--brand-red); font-weight: 700; font-size: 0.85rem;" title="{{ $article->rejection_reason }}">✕ Rejected</span>
                    @elseif($article->moderation_status === 'unpublished')
                        <span style="color: var(--text-secondary); font-weight: 700; font-size: 0.85rem;">⊘ Unpublished</span>
                    @else
                        <span style="color: #f59e0b; font-weight: 700; font-size: 0.85rem;">⏳ Pending</span>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    @php
                        $statusColor = $article->status === 'published' ? 'var(--success)' : 'var(--text-secondary)';
                    @endphp
                    <span style="display: inline-flex; align-items: center; gap: 0.5rem; color: {{ $statusColor }}; font-size: 0.85rem; font-weight: 600;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $statusColor }};"></span>
                        {{ ucfirst($article->status) }}
                    </span>
                </td>
                <td style="padding: 1rem; color: var(--text-secondary); font-size: 0.85rem;">
                    {{ $article->created_at->format('M d, Y') }}
                </td>
                <td style="padding: 1rem; text-align: right;">
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        @if(auth()->user()->canApproveArticles() && ($article->moderation_status === 'pending' || $article->moderation_status === 'rejected'))
                            <form action="{{ route('admin.articles.approve', $article) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; color: #10b981; border-color: #10b981;">Approve</button>
                            </form>
                        @endif
                        
                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; border-color: var(--border);">Edit</a>
                        
                        @if(auth()->user()->canApproveArticles() || $article->moderation_status !== 'approved')
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Delete this article?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; font-weight: 500; padding: 0.3rem;">Delete</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 2rem;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
