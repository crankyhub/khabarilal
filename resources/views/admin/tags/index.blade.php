@extends('layouts.admin')

@section('header', 'Content Tags')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3>Tag List</h3>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">+ Create Tag</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.9rem;">
                <th style="padding: 1rem;">NAME</th>
                <th style="padding: 1rem;">SLUG</th>
                <th style="padding: 1rem;">ARTICLE COUNT</th>
                <th style="padding: 1rem;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 1rem; font-weight: 600;">{{ $tag->name }}</td>
                <td style="padding: 1rem; color: var(--text-secondary);">{{ $tag->slug }}</td>
                <td style="padding: 1rem;">
                    <span style="padding: 0.25rem 0.5rem; background: rgba(59, 130, 246, 0.1); color: var(--accent); border-radius: 0.5rem; font-size: 0.8rem;">
                        {{ $tag->articles->count() }}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.tags.edit', $tag) }}" style="color: var(--accent); text-decoration: none; font-weight: 500;">Edit</a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
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
        {{ $tags->links() }}
    </div>
</div>
@endsection
