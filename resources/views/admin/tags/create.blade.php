@extends('layouts.admin')

@section('header', 'Create Tag')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.tags.store') }}" method="POST">
        @csrf
        <div class="form-group" style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.9rem; font-weight: 600;">TAG NAME</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Breaking News" required style="width: 100%; padding: 1rem; background: var(--bg-dark); border: 1px solid var(--border); border-radius: 0.75rem; color: white; font-family: inherit;">
            @error('name')
                <div style="color: var(--danger); font-size: 0.8rem; margin-top: 0.5rem;">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">Create Tag</button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline" style="flex: 1; text-align: center; text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>
@endsection
