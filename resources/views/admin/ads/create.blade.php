@extends('layouts.admin')

@section('header', 'Create Advertisement')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('admin.ads.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Ad Title (Internal Reference)</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Summer Sale 2026 Banner" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Ad Placement Type</label>
                <select name="type" class="form-control" required>
                    <option value="banner">Top Banner</option>
                    <option value="sidebar">Sidebar Widget</option>
                    <option value="in-article">In-Article Content</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Vertical Position / Key</label>
                <input type="text" name="position" class="form-control" placeholder="e.g. home_top, article_sidebar" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Destination URL (Optional)</label>
            <input type="url" name="link_url" class="form-control" placeholder="https://example.com/promo">
        </div>

        <div class="form-group">
            <label class="form-label">Ad Content (Internal Snippet or HTML)</label>
            <textarea name="content" class="form-control" rows="8" placeholder="Enter HTML snippet, AdSense code, or image URL..." required></textarea>
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">For simple image ads, you can just paste the image URL here.</p>
        </div>

        <div class="form-group" style="margin-top: 2rem; display: flex; align-items: center; gap: 0.75rem;">
            <input type="checkbox" name="is_active" id="is_active" checked value="1" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
            <label for="is_active" style="cursor: pointer; font-weight: 600;">Enable advertisement immediately</label>
        </div>

        <div style="margin-top: 3rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem;">Create Advertisement</button>
            <a href="{{ route('admin.ads.index') }}" class="btn btn-outline" style="padding: 1rem 2rem; border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
