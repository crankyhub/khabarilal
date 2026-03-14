@extends('layouts.admin')

@section('header', 'Add New Reporter')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('admin.reporters.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h4 style="margin-bottom: 1.5rem; color: var(--accent);">Account Details</h4>
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="password-input-group">
                <input type="password" name="password" class="form-control" required>
                <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </button>
            </div>
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Minimum 8 characters.</p>
        </div>

        <div class="form-group">
            <label class="form-label">User Role</label>
            <select name="role" class="form-control" required>
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ $value === 'reporter' ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border); margin: 2rem 0;">
        
        <h4 style="margin-bottom: 1.5rem; color: var(--accent);">Professional Profile</h4>
        <div class="form-group">
            <label class="form-label">Beat / Specialty</label>
            <input type="text" name="beat" class="form-control" placeholder="e.g. Sports, local Politics">
        </div>

        <div class="form-group">
            <label class="form-label">Primary Category</label>
            <select name="category_id" class="form-control">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Revenue Share (%)</label>
            <input type="number" name="revenue_share" class="form-control" value="0" min="0" max="100" required>
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Percentage of ad revenue shared with this reporter.</p>
        </div>

        <div class="form-group">
            <label class="form-label">Bio / Description</label>
            <textarea name="bio" class="form-control" rows="4" placeholder="Brief professional background..."></textarea>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <label class="form-label">Profile Photo</label>
            <input type="file" name="photo" class="form-control">
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">JPEG, PNG, JPG, WEBP (Max 1MB)</p>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Create Account</button>
            <a href="{{ route('admin.reporters.index') }}" class="btn btn-outline" style="text-decoration: none; border: 1px solid var(--border); color: var(--text-secondary); padding: 0.75rem 1.5rem; border-radius: 0.75rem;">Cancel</a>
        </div>
    </form>
</div>
@endsection
