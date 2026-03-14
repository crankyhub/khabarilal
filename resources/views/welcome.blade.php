<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ App\Models\Setting::get('site_name', 'Hindustan') }} - Hindi News Portal</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="icon" type="image/png" href="{{ App\Models\Setting::get('site_favicon') ? asset('storage/' . App\Models\Setting::get('site_favicon')) : '/favicon.png' }}">
    
    <style>
        :root {
            --header-bg: {{ App\Models\Setting::get('header_bg_color', '#f9c80e') }};
            --header-text: #ffffff;
        }
        .hindustan-header {
            background: var(--header-bg) !important;
            color: var(--header-text) !important;
        }
        .hindustan-header .header-util,
        .hindustan-header .util-item,
        .hindustan-header .logo-fallback-text,
        .hindustan-header .search-icon,
        .hindustan-header .sign-in {
            color: var(--header-text) !important;
        }
        .hindustan-header .burger-menu span {
            background: var(--header-text) !important;
        }
    </style>
</head>
<body class="brand-body">

    {{-- Universal Header --}}
    <header class="hindustan-header">
        <div class="container header-inner">
            <div class="header-left">
                <button class="burger-menu" id="drawer-toggle" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <a href="/" class="logo-link">
                    @if(App\Models\Setting::get('site_logo'))
                        <img src="{{ asset('storage/' . App\Models\Setting::get('site_logo')) }}" alt="Logo" style="height: {{ App\Models\Setting::get('site_logo_height', '45') }}px;">
                    @else
                        <div class="logo-fallback-icon">हि</div>
                        <h1 class="logo-fallback-text">हिन्दुस्तान</h1>
                    @endif
                </a>
                <div class="inshorts-toggle-btn" id="inshorts-toggle" onclick="toggleInshortView()">
                    ⚡ Inshorts
                </div>
            </div>

            <div class="header-util">
                <div class="util-item">🖼️ फोटो</div>
                <div class="util-item">📹 वीडियो</div>
                <div class="util-item mobile-hide">📍 शहर चुनें</div>
                <div class="util-item mobile-hide">📰 ई-पेपर</div>
                <div class="util-item sign-in">👤 साइन इन</div>
                <div class="search-box mobile-hide">
                    <input type="text" placeholder="यहाँ लिखें">
                    <span class="search-icon">🔍</span>
                </div>
            </div>
        </div>
    </header>

    {{-- Secondary Sticky Nav --}}
    <nav class="secondary-nav">
        <div class="container">
            <div class="pill-nav">
                <a href="/" class="pill-item {{ !isset($currentCategory) ? 'active' : '' }}">होम</a>
                @foreach(\App\Models\Category::whereNull('parent_id')->get() as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="pill-item {{ (isset($currentCategory) && $currentCategory->id == $cat->id) ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </nav>

    @include('partials.breaking-news')
    
    @php $topAd = \App\Helpers\AdHelper::getAd('top_banner', isset($currentCategory) ? $currentCategory->id : null); @endphp
    @if($topAd)
        <div class="container" style="margin-top: 1rem; text-align: center;">
            <div style="font-size: 0.65rem; color: #94a3b8; margin-bottom: 0.2rem; text-transform: uppercase;">Advertisement</div>
            {!! \App\Helpers\AdHelper::render($topAd) !!}
        </div>
    @endif

    <main class="container main-content-wrapper">
        


        <div class="layout-grid">
            <div class="main-column">
                {{-- Hero Section --}}
                @php $featured = $articles->first(); @endphp
                @if($featured)
                <div class="hero-hindustan">
                    <a href="{{ route('article.show', $featured->slug) }}" class="featured-big">
                        @if($featured->media_id && $featured->media)
                            <img src="{{ $featured->media->url }}">
                        @elseif($featured->image_path)
                            <img src="{{ asset('storage/' . $featured->image_path) }}">
                        @endif
                        <div class="featured-content">
                            <span class="trending-label">TRENDING NEWS</span>
                            <h2>{{ $featured->title }}</h2>
                        </div>
                    </a>

                    <div class="featured-list">
                        @foreach($articles->slice(1, 4) as $article)
                            <a href="{{ route('article.show', $article->slug) }}" class="list-item-h">
                                <div class="list-thumb">
                                    @if($article->media_id && $article->media)
                                        <img src="{{ $article->media->url }}">
                                    @elseif($article->image_path)
                                        <img src="{{ asset('storage/' . $article->image_path) }}">
                                    @endif
                                </div>
                                <div class="list-info">
                                    <h4>{{ $article->title }}</h4>
                                    <div class="list-meta">{{ $article->published_at ? $article->published_at->format('M d, Y h:i A') : $article->created_at->format('M d, Y h:i A') }} IST</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="article-feed" id="article-load-container">
                    @foreach($articles->slice(5) as $article)
                        @include('partials.article-item', ['article' => $article])
                    @endforeach
                </div>

                @if($articles->hasMorePages())
                    <div style="text-align: center; margin: 3rem 0;">
                        <button id="load-more-btn" data-page="1" class="load-more-btn">
                            <span class="btn-text">LOAD MORE STORIES</span>
                            <span class="btn-loader" style="display: none;">
                                <svg class="spinner" viewBox="0 0 50 50"><circle cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle></svg>
                            </span>
                        </button>
                    </div>
                @endif

                @php 
                    $feedAd = \App\Helpers\AdHelper::getAd('in_feed', isset($currentCategory) ? $currentCategory->id : null); 
                @endphp
                @if($feedAd)
                    <div style="margin: 2rem 0;">
                        {!! \App\Helpers\AdHelper::render($feedAd) !!}
                    </div>
                @endif

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const loadMoreBtn = document.getElementById('load-more-btn');
                        const container = document.getElementById('article-load-container');

                        if (loadMoreBtn) {
                            loadMoreBtn.addEventListener('click', function() {
                                const page = parseInt(this.getAttribute('data-page')) + 1;
                                const btnText = this.querySelector('.btn-text');
                                const btnLoader = this.querySelector('.btn-loader');

                                // Show loader
                                btnText.style.display = 'none';
                                btnLoader.style.display = 'inline-block';
                                this.disabled = true;

                                fetch(`${window.location.pathname}?page=${page}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.html) {
                                        container.insertAdjacentHTML('beforeend', data.html);
                                        this.setAttribute('data-page', page);
                                        
                                        // Hide button if no more pages
                                        if (!data.hasMore) {
                                            this.parentElement.style.display = 'none';
                                        }
                                    }
                                    
                                    // Reset button
                                    btnText.style.display = 'inline-block';
                                    btnLoader.style.display = 'none';
                                    this.disabled = false;
                                })
                                .catch(error => {
                                    console.error('Error loading more articles:', error);
                                    btnText.style.display = 'inline-block';
                                    btnLoader.style.display = 'none';
                                    this.disabled = false;
                                });
                            });
                        }
                    });
                </script>
            </div>

            {{-- Sidebar --}}
            <aside class="site-sidebar">
                @php 
                    $catId = isset($currentCategory) ? $currentCategory->id : null;
                    $sidebarAd = \App\Helpers\AdHelper::getAd('sidebar', $catId); 
                @endphp
                @if($sidebarAd)
                    {!! \App\Helpers\AdHelper::render($sidebarAd) !!}
                @else
                    <div class="ad-container">
                        <div class="ad-label">विज्ञापन</div>
                        <div class="ad-box">SIDBEAR AD 300x250</div>
                    </div>
                @endif

                <div class="must-read-card">
                    <div class="must-read-title">जरूर पढ़ें</div>
                    @foreach(\App\Models\Article::where('status', 'published')->inRandomOrder()->take(5)->get() as $must)
                        <a href="{{ route('article.show', $must->slug) }}" class="sidebar-item">
                            <div class="sidebar-thumb">
                                @if($must->media_id && $must->media)
                                    <img src="{{ $must->media->url }}">
                                @elseif($must->image_path)
                                    <img src="{{ asset('storage/' . $must->image_path) }}">
                                @endif
                            </div>
                            <div class="sidebar-info">{{ $must->title }}</div>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                @if(App\Models\Setting::get('site_logo'))
                    <img src="{{ asset('storage/' . App\Models\Setting::get('site_logo')) }}" alt="Logo" style="height: {{ App\Models\Setting::get('site_logo_height', '45') }}px; margin-bottom: 1rem;">
                @else
                    <div class="footer-logo">हिन्दुस्तान</div>
                @endif
                <p>भारत का अग्रणी समाचार पोर्टल। नवीनतम समाचारों के लिए जुड़े रहें।</p>
            </div>
            <div class="footer-contact">
                <h4>संपर्क</h4>
                <p>ईमेल: support@khabarilal.com<br>फोन: +91 120 1234567</p>
            </div>
            <div class="footer-goto-top">
                <button onclick="scrollToTop()" title="Go to Top">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                </button>
            </div>
        </div>
    </footer>

    {{-- Popup Ad --}}
    @php $popupAd = \App\Helpers\AdHelper::getAd('popup', isset($currentCategory) ? $currentCategory->id : null); @endphp
    @if($popupAd)
        <div id="ad-popup-overlay" class="ad-popup-overlay">
            <div class="ad-popup-container">
                <button class="ad-popup-close" onclick="document.getElementById('ad-popup-overlay').style.display='none'">✕</button>
                {!! \App\Helpers\AdHelper::render($popupAd) !!}
            </div>
        </div>
    @endif
    {{-- Mobile Drawer --}}
    <div class="drawer-overlay" id="drawer-overlay"></div>
    <div class="mobile-drawer" id="mobile-drawer">
        <div class="drawer-header">
            <a href="/" class="drawer-logo">Khabari Laal</a>
            <button class="drawer-close" id="drawer-close">✕</button>
        </div>
        <nav class="drawer-nav">
            <a href="/" class="drawer-item">होम</a>
            @foreach(\App\Models\Category::whereNull('parent_id')->get() as $cat)
                <a href="{{ route('category.show', $cat->slug) }}" class="drawer-item">{{ $cat->name }}</a>
            @endforeach
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('drawer-toggle');
            const close = document.getElementById('drawer-close');
            const drawer = document.getElementById('mobile-drawer');
            const overlay = document.getElementById('drawer-overlay');
            const body = document.body;

            function toggleDrawer() {
                drawer.classList.toggle('active');
                overlay.classList.toggle('active');
                body.classList.toggle('drawer-open');
            }

            toggle.addEventListener('click', toggleDrawer);
            close.addEventListener('click', toggleDrawer);
            overlay.addEventListener('click', toggleDrawer);
        });

        const articles = {!! json_encode($articles->take(20)->map(function($a) {
            return [
                'title' => $a->title,
                'content' => $a->summary ? strip_tags($a->summary) : \Str::limit(strip_tags($a->body), 200),
                'image' => ($a->media_id && $a->media) ? $a->media->url : ($a->image_path ? asset('storage/'.$a->image_path) : null),
                'url' => route('article.show', $a->slug),
                'meta' => ($a->published_at ? $a->published_at->format('M d, Y') : $a->created_at->format('M d, Y')) . ' • Khabari Laal'
            ];
        })->toArray()) !!};

        function toggleInshortView() {
            const container = document.getElementById('inshorts-container');
            const body = document.body;
            const btn = document.getElementById('inshorts-toggle');
            
            if (container.classList.contains('active')) {
                container.classList.remove('active');
                body.classList.remove('inshorts-active');
                btn.classList.remove('active');
            } else {
                if (container.children.length === 0) {
                    renderInshorts();
                }
                container.classList.add('active');
                body.classList.add('inshorts-active');
                btn.classList.add('active');
            }
        }

        function renderInshorts() {
            const container = document.getElementById('inshorts-container');
            container.innerHTML = '<button class="inshorts-close" onclick="toggleInshortView()">✕</button>';
            
            articles.forEach(article => {
                const card = document.createElement('div');
                card.className = 'inshorts-card';
                card.innerHTML = `
                    <div class="inshorts-card-img">
                        ${article.image ? `<img src="${article.image}" alt="">` : ''}
                    </div>
                    <div class="inshorts-card-content">
                        <h2>${article.title}</h2>
                        <p>${article.content}</p>
                        <div class="inshorts-card-footer">
                            <span class="inshorts-meta">${article.meta}</span>
                            <a href="${article.url}" class="inshorts-read-more">Read Full Story →</a>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        window.addEventListener('scroll', function() {
            const btn = document.querySelector('.footer-goto-top');
            if (window.pageYOffset > 300) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        });
    </script>
    <div class="inshorts-container" id="inshorts-container"></div>
</body>
</html>
