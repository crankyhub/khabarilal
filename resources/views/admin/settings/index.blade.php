@extends('layouts.admin')

@section('title', 'Brand Management')

@section('content')
<div class="card">
    <h2 style="margin-bottom: 2rem;">Brand Settings</h2>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Site Name</label>
            <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Site Logo</label>
            @if($settings['site_logo'])
                <div style="margin-bottom: 1rem;">
                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" style="height: {{ $settings['site_logo_height'] }}px; background: #eee; padding: 0.5rem; border-radius: 0.5rem;">
                </div>
            @endif
            <input type="file" name="site_logo" class="form-control">
            <small style="color: var(--text-secondary);">Recommended size: 200x50px (PNG/JPG)</small>
        </div>

        <div class="form-group">
            <label class="form-label">Logo Height (px)</label>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <input type="range" min="20" max="150" value="{{ $settings['site_logo_height'] }}" 
                       oninput="this.nextElementSibling.value = this.value; document.getElementById('logo_height_num').value = this.value" 
                       style="flex: 1; accent-color: var(--accent-red);">
                <input type="number" name="site_logo_height" id="logo_height_num" 
                       value="{{ $settings['site_logo_height'] }}" 
                       class="form-control" style="width: 80px;" required>
            </div>
            <small style="color: var(--text-secondary);">Adjust height from 20px to 150px.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Site Favicon</label>
            @if($settings['site_favicon'])
                <div style="margin-bottom: 1rem;">
                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" style="width: 32px; height: 32px; background: #eee; padding: 0.2rem; border-radius: 0.2rem;">
                </div>
            @endif
            <input type="file" name="site_favicon" class="form-control">
            <small style="color: var(--text-secondary);">Recommended size: 32x32px (ICO/PNG)</small>
        </div>

        <div class="form-group">
            <label class="form-label">Header Background Color</label>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <input type="color" name="header_bg_color" id="header_bg_color" 
                       value="{{ $settings['header_bg_color'] }}" 
                       class="form-control" style="width: 100px; height: 45px; padding: 2px;">
                <button type="button" class="btn" style="background: #eee; color: #333;" onclick="randomizeHeaderColor()">
                    🎲 Randomize
                </button>
            </div>
            <small style="color: var(--text-secondary);">This will change the main header's background color.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Breaking News Speed (seconds)</label>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <input type="range" min="5" max="100" value="{{ $settings['ticker_speed'] }}" 
                       oninput="this.nextElementSibling.value = this.value; document.getElementById('ticker_speed_num').value = this.value" 
                       style="flex: 1; accent-color: var(--accent-red);">
                <input type="number" name="ticker_speed" id="ticker_speed_num" 
                       value="{{ $settings['ticker_speed'] }}" 
                       class="form-control" style="width: 80px;" required>
            </div>
            <small style="color: var(--text-secondary);">Higher value means slower scrolling. Default: 15s.</small>
        </div>

        <script>
            function randomizeHeaderColor() {
                const randomColor = '#' + Math.floor(Math.random()*16777215).toString(16).padStart(6, '0');
                document.getElementById('header_bg_color').value = randomColor;
            }
        </script>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Save Branding</button>
        </div>
    </form>
</div>
@endsection
