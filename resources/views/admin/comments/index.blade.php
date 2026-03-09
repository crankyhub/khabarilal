@extends('layouts.admin')

@section('header', 'Comment Moderation')

@section('content')
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.8rem;">
                <th style="padding: 1rem;">STATUS</th>
                <th style="padding: 1rem;">USER & COMMENT</th>
                <th style="padding: 1rem;">ARTICLE</th>
                <th style="padding: 1rem; text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 1rem;">
                    @if($comment->is_approved)
                        <span style="color: #10b981; font-weight: 700; font-size: 0.75rem;">APPROVED</span>
                    @else
                        <span style="color: #f59e0b; font-weight: 700; font-size: 0.75rem;">PENDING</span>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    <div style="font-weight: 600;">{{ $comment->user_name }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.2rem;">{{ $comment->content }}</div>
                </td>
                <td style="padding: 1rem; font-size: 0.8rem;">
                    <a href="{{ route('article.show', $comment->article->slug) }}" target="_blank" style="color: var(--accent); text-decoration: none;">
                        {{ Str::limit($comment->article->title, 40) }}
                    </a>
                </td>
                <td style="padding: 1rem; text-align: right;">
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        @if(!$comment->is_approved)
                            <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="font-size: 0.75rem; padding: 0.3rem 0.7rem;">Approve</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.3rem 0.7rem; border: 1px solid var(--danger); color: var(--danger); background: transparent; cursor: pointer;">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 2rem;">
        {{ $comments->links() }}
    </div>
</div>
@endsection
