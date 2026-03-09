@extends('layouts.admin')

@section('header', 'System Health Monitor')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
    <div class="card">
        <h3 style="margin-bottom: 2rem;">Resource Usage</h3>
        <div style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                <span>Total Disk Usage</span>
                <span style="font-weight: 700;">{{ $stats['disk_usage'] }}%</span>
            </div>
            <div style="width: 100%; height: 8px; background: var(--bg-dark); border-radius: 1rem; overflow: hidden;">
                <div style="width: {{ $stats['disk_usage'] }}%; height: 100%; background: {{ $stats['disk_usage'] > 80 ? 'var(--danger)' : '#10b981' }};"></div>
            </div>
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">{{ $stats['disk_free'] }} GB available on primary storage disk.</p>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom: 2rem;">Platform Status</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 0.75rem; background: var(--bg-dark); border-radius: 0.5rem;">
                <span style="color: var(--text-secondary);">Database</span>
                <span style="color: #10b981; font-weight: 700;">{{ $stats['db_connection'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 0.75rem; background: var(--bg-dark); border-radius: 0.5rem;">
                <span style="color: var(--text-secondary);">Environment</span>
                <span style="font-weight: 700; text-transform: uppercase;">{{ $stats['env'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 0.75rem; background: var(--bg-dark); border-radius: 0.5rem;">
                <span style="color: var(--text-secondary);">Debug Mode</span>
                <span style="color: {{ $stats['debug'] == 'Enabled' ? 'var(--danger)' : 'var(--text-secondary)' }}; font-weight: 700;">{{ $stats['debug'] }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 1.5rem;">
    <h3 style="margin-bottom: 1.5rem;">Framework Info</h3>
    <div style="font-size: 0.9rem; color: var(--text-secondary);">
        Running Laravel <span style="color: var(--accent); font-weight: 700;">v{{ $stats['laravel_version'] }}</span> on PHP <span style="color: var(--text-primary); font-weight: 700;">{{ PHP_VERSION }}</span>
    </div>
</div>
@endsection
