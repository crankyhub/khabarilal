@extends('layouts.admin')

@section('header', 'Newsletter Subscribers')

@section('content')
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.8rem;">
                <th style="padding: 1rem;">EMAIL ADDRESS</th>
                <th style="padding: 1rem;">SUBSCRIBED ON</th>
                <th style="padding: 1rem; text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscribers as $sub)
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 1rem; font-weight: 600;">{{ $sub->email }}</td>
                <td style="padding: 1rem; color: var(--text-secondary); font-size: 0.9rem;">{{ \Carbon\Carbon::parse($sub->created_at)->format('M d, Y') }}</td>
                <td style="padding: 1rem; text-align: right;">
                    <form action="{{ route('admin.newsletter.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Unsubscribe this user?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.3rem 0.7rem; border: 1px solid var(--danger); color: var(--danger); background: transparent; cursor: pointer;">Remove</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 2rem;">
        {{ $subscribers->links() }}
    </div>
</div>
@endsection
