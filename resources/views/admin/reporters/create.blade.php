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
            <input type="password" name="password" class="form-control" required>
            <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">Minimum 8 characters.</p>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--border); margin: 2rem 0;">
        
        <h4 style="margin-bottom: 1.5rem; color: var(--accent);">Professional Profile</h4>
        <div class="form-group">
            <label class="form-label">Beat / Specialty</label>
            <input type="text" name="beat" class="form-control" placeholder="e.g. Sports, local Politics">
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
