@extends('layouts.admin')

@section('header', 'News Reporters & Journalists')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h3>Reporting Staff Management</h3>
            <p style="font-size: 0.9rem; color: var(--text-secondary);">Manage permissions, roles, and track performance.</p>
        </div>
        <a href="{{ route('admin.reporters.create') }}" class="btn btn-primary">+ Add New Staff</a>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--border); color: var(--text-secondary); font-size: 0.9rem;">
                <th style="padding: 1rem;">STAFF MEMBER</th>
                <th style="padding: 1rem;">ROLE & STATUS</th>
                <th style="padding: 1rem;">BEAT / CATEGORY</th>
                <th style="padding: 1rem; text-align: center;">ARTICLES</th>
                <th style="padding: 1rem; text-align: center;">RATING</th>
                <th style="padding: 1rem; text-align: right;">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reporters as $reporter)
            <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='none'">
                <td style="padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--brand-red); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem;">
                            {{ substr($reporter->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 700; color: var(--brand-black);">{{ $reporter->user->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $reporter->user->email }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding: 1rem;">
                    <div style="margin-bottom: 0.25rem;">
                        <span style="padding: 0.2rem 0.5rem; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                            {{ str_replace('_', ' ', $reporter->user->role) }}
                        </span>
                    </div>
                    @if($reporter->user->status === 'active')
                        <span style="color: #10b981; font-size: 0.75rem;">● Active</span>
                    @else
                        <span style="color: var(--brand-red); font-size: 0.75rem;">● {{ ucfirst($reporter->user->status) }}</span>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $reporter->beat ?? 'General' }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $reporter->category->name ?? 'Mixed' }}</div>
                </td>
                <td style="padding: 1rem; text-align: center;">
                    <span style="font-weight: 800;">{{ $reporter->articles_count }}</span>
                </td>
                <td style="padding: 1rem; text-align: center;">
                    <div style="color: #f59e0b; font-weight: 700;">★ {{ number_format($reporter->rating_average, 1) }}</div>
                </td>
                <td style="padding: 1rem; text-align: right;">
                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center;">
                        <a href="{{ route('admin.reporters.edit', $reporter) }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-color: var(--border);">Manage</a>
                        
                        <form action="{{ route('admin.reporters.toggle-status', $reporter->user) }}" method="POST">
                            @csrf
                            <button type="submit" style="background: none; border: none; cursor: pointer; padding: 0;">
                                @if($reporter->user->status === 'active')
                                    <span title="Suspend Reporter" style="font-size: 1.2rem;">🚫</span>
                                @else
                                    <span title="Activate Reporter" style="font-size: 1.2rem;">✅</span>
                                @endif
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
