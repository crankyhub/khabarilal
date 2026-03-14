<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Khabar-i-Lal Admin' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="manifest" href="/manifest.json">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '.editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        skin: 'oxide-dark',
        content_css: 'dark'
      });
    </script>
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <button id="sidebarToggle" class="sidebar-toggle">
            <span id="toggleIcon">◀</span>
        </button>

        <aside id="sidebar" class="sidebar">
            <div class="brand brand-full">Khabar-i-Lal</div>
            <div class="brand brand-icon" style="display: none; color: var(--accent); font-weight: 800;">हि</div>
            
            <ul class="nav-links">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">📊</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.articles.index') }}" class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">📰</span>
                        <span>Articles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">📂</span>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.tags.index') }}" class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">🏷️</span>
                        <span>Tags</span>
                    </a>
                </li>
                @if(auth()->user()->canManageUsers())
                <li class="nav-item">
                    <a href="{{ route('admin.reporters.index') }}" class="nav-link {{ request()->routeIs('admin.reporters.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">👥</span>
                        <span>Reporters</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.media.index') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">🖼️</span>
                        <span>Media Gallery</span>
                    </a>
                </li>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isEditor())
                <li class="nav-item">
                    <a href="{{ route('admin.ads.index') }}" class="nav-link {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">📢</span>
                        <span>Ads Manager</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">💬</span>
                        <span>Comments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.newsletter.index') }}" class="nav-link {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">📧</span>
                        <span>Subscribers</span>
                    </a>
                </li>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isEditor())
                <li class="nav-item">
                    <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">🔔</span>
                        <span>Push Alerts</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->isSuperAdmin())
                <li class="nav-item">
                    <a href="{{ route('admin.health.index') }}" class="nav-link {{ request()->routeIs('admin.health.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">🩺</span>
                        <span>System Health</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">🎨</span>
                        <span>Brand Settings</span>
                    </a>
                </li>
                @endif

            </ul>
            <div class="user-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width: 100%; display: flex; align-items: center; background: none; border: 1px solid var(--border); color: var(--text-secondary);">
                        <span style="font-size: 1.2rem; margin-right: 0.75rem;">🚪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main id="mainContent" class="main-content">
            <header class="main-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>{{ $header ?? 'Dashboard' }}</h2>
                <div class="user-info" style="display: flex; align-items: center; gap: 1rem;">
                    <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                    <a href="{{ route('admin.profile.password.edit') }}" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.75rem; border-color: var(--border);">🔐 Change Password</a>
                </div>
            </header>

            @if(session('success'))
                <div class="alert alert-success" style="background: var(--success); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('impersonator_id'))
                <div style="background: var(--brand-yellow); color: var(--brand-black); padding: 0.75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; margin: -2rem -2rem 2rem -2rem; font-weight: 700; border-bottom: 2px solid var(--brand-red);">
                    <span>⚠️ YOU ARE CURRENTLY IMPERSONATING: {{ auth()->user()->name }}</span>
                    <form action="{{ route('admin.stop-impersonating') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.8rem; background: var(--brand-red);">Return to Admin</button>
                    </form>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');

        // Apply saved state
        const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            sidebarToggle.classList.add('collapsed');
            toggleIcon.innerText = '▶';
        }

        sidebarToggle.addEventListener('click', () => {
            const nowCollapsed = sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            sidebarToggle.classList.toggle('collapsed');
            
            toggleIcon.innerText = nowCollapsed ? '▶' : '◀';
            localStorage.setItem('adminSidebarCollapsed', nowCollapsed);
        });

        function togglePassword(btn) {
            const input = btn.closest('.password-input-group').querySelector('input');
            const showIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.399 8.049 7.21 5 12 5c4.79 0 8.601 3.049 9.964 6.678.045.122.045.28 0 .402-1.364 3.629-5.174 6.678-9.964 6.678-4.79 0-8.601-3.049-9.964-6.678z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;
            const hideIcon = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>`;

            if (input.type === "password") {
                input.type = "text";
                btn.innerHTML = hideIcon;
            } else {
                input.type = "password";
                btn.innerHTML = showIcon;
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
