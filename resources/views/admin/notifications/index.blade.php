@extends('layouts.admin')

@section('header', 'Push Notifications')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div class="card">
        <h3>Broadcast New Alert</h3>
        <p style="color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.9rem;">Send a breaking news alert to all subscribed readers.</p>

        <form action="{{ route('admin.notifications.send') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Notification Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. BREAKING: Major event just happened!" required>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Message Body</label>
                <textarea name="body" class="form-control" rows="3" placeholder="A short description..."></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label">Target URL</label>
                <input type="url" name="url" id="target_url" class="form-control" placeholder="https://khabarilal.com/article/slug" required>
                <div style="margin-top: 1rem; padding: 1rem; background: var(--bg-dark); border-radius: 0.5rem;">
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Quick Select Recent Article:</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($recentArticles as $article)
                            <button type="button" class="btn btn-outline" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;" onclick="document.getElementById('target_url').value = '{{ route('article.show', $article->slug) }}'">
                                {{ Str::limit($article->title, 30) }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 700;">🚀 BROADCAST NOTIFICATION</button>
        </form>
    </div>

    <div class="card" style="height: fit-content;">
        <h3>Audience Stats</h3>
        <div style="text-align: center; padding: 2rem 0;">
            <div style="font-size: 3rem; font-weight: 800; color: var(--accent);">{{ $subscriberCount }}</div>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">Active Subscriptions</p>
        </div>
        
        <div style="margin-top: 2rem; padding: 1rem; border-top: 1px solid var(--border);">
            <p style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.6;">
                <strong>Note:</strong> Notifications are delivered via browser push workers. Ensure your VAPID keys are configured in production for real delivery.
            </p>
        </div>
    </div>
</div>
@endsection
