@extends('layouts.admin')

@section('header', 'Edit Advertisement')

@section('content')
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Ad Title (Internal Reference)</label>
            <input type="text" name="title" class="form-control" value="{{ $ad->title }}" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Ad Placement Type</label>
                <select name="type" class="form-control" required>
                    <option value="banner" {{ $ad->type == 'banner' ? 'selected' : '' }}>Top Banner</option>
                    <option value="sidebar" {{ $ad->type == 'sidebar' ? 'selected' : '' }}>Sidebar Widget</option>
                    <option value="in-article" {{ $ad->type == 'in-article' ? 'selected' : '' }}>In-Article Content</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Vertical Position / Key</label>
                <input type="text" name="position" class="form-control" value="{{ $ad->position }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Destination URL (Optional)</label>
            <input type="url" name="link_url" class="form-control" value="{{ $ad->link_url }}">
        </div>

        <div class="form-group">
            <label class="form-label">Ad Content (Internal Snippet or HTML)</label>
            <textarea name="content" class="form-control" rows="8" required>{{ $ad->content }}</textarea>
        </div>

        <div class="form-group" style="margin-top: 2rem; display: flex; align-items: center; gap: 0.75rem;">
            <input type="checkbox" name="is_active" id="is_active" {{ $ad->is_active ? 'checked' : '' }} value="1" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
            <label for="is_active" style="cursor: pointer; font-weight: 600;">Advertisement is active</label>
        </div>

        <div style="margin-top: 3rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem;">Update Advertisement</button>
            <a href="{{ route('admin.ads.index') }}" class="btn btn-outline" style="padding: 1rem 2rem; border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
