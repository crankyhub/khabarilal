@extends('layouts.admin')

@section('header', 'News Categories')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3>Category List</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Create Category</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.9rem;">
                <th style="padding: 1rem;">NAME</th>
                <th style="padding: 1rem;">SLUG</th>
                <th style="padding: 1rem;">PARENT</th>
                <th style="padding: 1rem;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 1rem; font-weight: 600;">{{ $category->name }}</td>
                <td style="padding: 1rem; color: var(--text-secondary);">{{ $category->slug }}</td>
                <td style="padding: 1rem;">
                    @if($category->parent)
                        <span style="padding: 0.25rem 0.5rem; background: rgba(59, 130, 246, 0.2); color: var(--accent); border-radius: 0.5rem; font-size: 0.8rem;">
                            {{ $category->parent->name }}
                        </span>
                    @else
                        <span style="color: var(--text-secondary); font-size: 0.8rem;">None</span>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.categories.edit', $category) }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; font-weight: 500; font-family: inherit;">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 2rem;">
        {{ $categories->links() }}
    </div>
</div>
@endsection
