@extends('layouts.admin')

@section('title', 'Change Password')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2 style="margin-bottom: 2rem;">Change Your Password</h2>

    <form action="{{ route('admin.profile.password.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Current Password</label>
            <div class="password-input-group">
                <input type="password" name="current_password" class="form-control" required>
                <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </button>
            </div>
            @error('current_password')
                <span style="color: var(--brand-red); font-size: 0.85rem;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">New Password</label>
            <div class="password-input-group">
                <input type="password" name="password" class="form-control" required>
                <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </button>
            </div>
            @error('password')
                <span style="color: var(--brand-red); font-size: 0.85rem;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <div class="password-input-group">
                <input type="password" name="password_confirmation" class="form-control" required>
                <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </button>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
    </form>
</div>
@endsection
