@extends('layouts.admin')

@section('header', 'Create Category')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Sports, Politics" required autofocus>
            @error('name') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Parent Category (Optional)</label>
            <select name="parent_id" class="form-control">
                <option value="">None (Top Level)</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline" style="text-decoration: none; border: 1px solid var(--border); color: var(--text-secondary); padding: 0.75rem 1.5rem; border-radius: 0.75rem;">Cancel</a>
        </div>
    </form>
</div>
@endsection
