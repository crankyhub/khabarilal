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
                <div class="user-info">
                    <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            @if(session('success'))
                <div class="alert alert-success" style="background: var(--success); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    {{ session('success') }}
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
    </script>
    @yield('scripts')
</body>
</html>
