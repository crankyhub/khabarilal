@extends('layouts.admin')

@section('header', 'Advertisement Manager')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="font-size: 1.5rem; letter-spacing: -0.02em;">Managed Campaigns</h2>
    <a href="{{ route('admin.ads.create') }}" class="btn btn-primary" style="text-decoration: none;">+ Create Ad</a>
</div>

<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.8rem;">
                <th style="padding: 1rem;">STATUS</th>
                <th style="padding: 1rem;">TITLE & TYPE</th>
                <th style="padding: 1rem;">POSITION</th>
                <th style="padding: 1rem; text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ads as $ad)
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 1rem;">
                    @if($ad->is_active)
                        <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.2rem 0.6rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700;">ACTIVE</span>
                    @else
                        <span style="background: rgba(100, 116, 139, 0.1); color: var(--text-secondary); padding: 0.2rem 0.6rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700;">INACTIVE</span>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    <div style="font-weight: 600; color: var(--text-primary);">{{ $ad->title }}</div>
                    <div style="font-size: 0.75rem; color: var(--accent); text-transform: uppercase; font-weight: 700; margin-top: 0.2rem;">{{ $ad->type }}</div>
                </td>
                <td style="padding: 1rem; font-size: 0.85rem; color: var(--text-secondary);">
                    {{ $ad->position }}
                </td>
                <td style="padding: 1rem; text-align: right;">
                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('admin.ads.edit', $ad->id) }}" class="btn btn-outline" style="font-size: 0.75rem; padding: 0.3rem 0.7rem; text-decoration: none; border: 1px solid var(--border); color: var(--text-secondary);">Edit</a>
                        <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Remove this advertisement?');">
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
        {{ $ads->links() }}
    </div>
</div>
@endsection
