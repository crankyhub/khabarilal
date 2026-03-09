@extends('layouts.admin')

@section('header', 'Edit Category: ' . $category->name)

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required autofocus>
            @error('name') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Parent Category (Optional)</label>
            <select name="parent_id" class="form-control">
                <option value="">None (Top Level)</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4">{{ $category->description }}</textarea>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline" style="text-decoration: none; border: 1px solid var(--border); color: var(--text-secondary); padding: 0.75rem 1.5rem; border-radius: 0.75rem;">Cancel</a>
        </div>
    </form>
</div>
@endsection
