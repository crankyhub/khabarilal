@extends('layouts.admin')

@section('header', 'System Health & Audit Logs')

@section('content')
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.8rem;">
                <th style="padding: 1rem;">TIMESTAMP</th>
                <th style="padding: 1rem;">USER</th>
                <th style="padding: 1rem;">ACTION</th>
                <th style="padding: 1rem;">ENTITY</th>
                <th style="padding: 1rem;">IP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr style="border-bottom: 1px solid var(--border);">
                <td style="padding: 1rem; font-size: 0.8rem; color: var(--text-secondary);">
                    {{ $log->created_at->format('M d, H:i:s') }}
                </td>
                <td style="padding: 1rem;">
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $log->user->name ?? 'System' }}</div>
                </td>
                <td style="padding: 1rem;">
                    <span style="font-weight: 700; font-size: 0.75rem; text-transform: uppercase; color: {{ $log->action == 'deleted' ? 'var(--danger)' : ($log->action == 'created' ? '#10b981' : '#3b82f6') }}">
                        {{ $log->action }}
                    </span>
                </td>
                <td style="padding: 1rem; font-size: 0.85rem;">
                    <span style="color: var(--text-secondary);">{{ class_basename($log->model_type) }}</span> (ID: {{ $log->model_id }})
                </td>
                <td style="padding: 1rem; font-size: 0.8rem; color: var(--text-secondary);">
                    {{ $log->ip_address }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 1.5rem;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
