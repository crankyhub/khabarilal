@extends('layouts.admin')

@section('header', 'Manage Reporter: ' . $reporter->user->name)

@section('content')
<div class="card">
    <form action="{{ route('admin.reporters.update', $reporter) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem;">
            <!-- Left: Stats & Info -->
            <div class="reporter-sidebar">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="width: 120px; height: 120px; border-radius: 50%; background: var(--brand-red); margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #fff; font-weight: 800; border: 5px solid #eee;">
                        {{ substr($reporter->user->name, 0, 1) }}
                    </div>
                    <h3 style="margin-bottom: 0.25rem;">{{ $reporter->user->name }}</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Member since {{ $reporter->user->created_at->format('M Y') }}</p>
                </div>

                <div class="card" style="background: var(--bg-body); border: none; margin-bottom: 1.5rem;">
                    <h5 style="margin-bottom: 1rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Security Settings</h5>
                    <div class="form-group">
                        <label class="form-label">Reset Password</label>
                        <div class="password-input-group">
                            <input type="password" name="password" class="form-control" autocomplete="new-password">
                            <button type="button" class="password-toggle-btn" onclick="togglePassword(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </button>
                        </div>
                        <small style="color: var(--text-secondary);">Leave blank to keep current password.</small>
                        @error('password')
                            <div style="color: var(--brand-red); font-size: 0.8rem; margin-top: 0.2rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card" style="background: var(--bg-body); border: none; margin-bottom: 1.5rem;">
                    <h5 style="margin-bottom: 1rem; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Performance Status</h5>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: center;">
                        <div style="background: #fff; padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border);">
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Avg Rating</div>
                            <div style="font-size: 1.25rem; font-weight: 800; color: #f59e0b;">{{ number_format($reporter->rating_average, 1) }} ★</div>
                        </div>
                        <div style="background: #fff; padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border);">
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Rev Share</div>
                            <div style="font-size: 1.25rem; font-weight: 800; color: var(--brand-red);">{{ number_format($reporter->revenue_share, 0) }}%</div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Role</label>
                    <select name="role" class="form-control">
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" {{ $reporter->user->role === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Account Status</label>
                    <select name="status" class="form-control">
                        <option value="active" {{ $reporter->user->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ $reporter->user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="banned" {{ $reporter->user->status === 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
                </div>
            </div>

            <!-- Right: Details & Links -->
            <div>
                <h4 style="border-bottom: 2px solid var(--brand-red); display: inline-block; padding-bottom: 0.5rem; margin-bottom: 2rem;">Professional Profile</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Beat / Focus Area</label>
                        <input type="text" name="beat" class="form-control" value="{{ $reporter->beat }}" placeholder="e.g. Political Crimes, Sports">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assigned Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">General / All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $reporter->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Journalist Biography</label>
                    <textarea name="bio" class="form-control" rows="4" placeholder="Brief professional background...">{{ $reporter->bio }}</textarea>
                </div>

                <div style="margin-top: 2rem;">
                    <h5 style="margin-bottom: 1rem;">Social Media Links</h5>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Twitter/X Profile</label>
                            <input type="text" name="social_links[twitter]" class="form-control" value="{{ $reporter->social_links['twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Facebook Profile</label>
                            <input type="text" name="social_links[facebook]" class="form-control" value="{{ $reporter->social_links['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Revenue Share Percentage (%)</label>
                    <input type="number" name="revenue_share" class="form-control" value="{{ $reporter->revenue_share }}" step="0.5" min="0" max="100">
                </div>

                <div style="margin-top: 3rem; display: flex; gap: 1rem; justify-content: flex-end; border-top: 1px solid var(--border); padding-top: 2rem;">
                    <a href="{{ route('admin.reporters.index') }}" class="btn btn-outline" style="border: 1px solid var(--border); color: var(--text-secondary); text-decoration: none;">Cancel Changes</a>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">Save Staff Settings</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
