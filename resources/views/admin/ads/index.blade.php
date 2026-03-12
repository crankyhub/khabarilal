@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0 text-gray-800">Advertisement Manager</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Campaign
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Campaign Name</th>
                            <th>Targeting</th>
                            <th>Active Positions</th>
                            <th>Stats (Imp/Click)</th>
                            <th>Remaining Credits</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ads as $ad)
                            <tr>
                                <td>
                                    <strong>{{ $ad->title }}</strong>
                                    <div class="small text-muted">Range: {{ $ad->start_date ? $ad->start_date->format('M d') : 'Open' }} - {{ $ad->end_date ? $ad->end_date->format('M d') : 'Open' }}</div>
                                </td>
                                <td>
                                    @if($ad->article)
                                        <span class="badge badge-info">Article: {{ $ad->article->title }}</span>
                                    @elseif($ad->category)
                                        <span class="badge badge-secondary">Category: {{ $ad->category->name }}</span>
                                    @else
                                        <span class="badge badge-light">Universal</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($ad->placements as $placement)
                                        <span class="badge badge-outline-primary" style="border: 1px solid #4e73df; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; margin-right: 2px;">
                                            {{ ucwords(str_replace('_', ' ', $placement->position)) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="small">
                                        👁️ {{ $ad->current_impressions }} / {{ $ad->limit_impressions ?: '∞' }}<br>
                                        🖱️ {{ $ad->current_clicks }} / {{ $ad->limit_clicks ?: '∞' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-{{ $ad->remaining_budget > 0 ? 'success' : 'danger' }}">
                                        ₹{{ number_format($ad->remaining_budget, 2) }}
                                    </div>
                                    <div class="small text-muted">Total: ₹{{ number_format($ad->total_budget, 2) }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ad->status === 'active' ? 'success' : ($ad->status === 'paused' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($ad->status) }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                        <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none; border-radius: 4px;">Edit</a>
                                        
                                        <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this campaign?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; font-weight: 500; padding: 0.3rem; font-size: 0.75rem;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $ads->links() }}
        </div>
    </div>
</div>
@endsection
