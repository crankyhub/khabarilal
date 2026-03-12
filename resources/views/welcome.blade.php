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
                <a href="/" class="logo-link">
                    @if(App\Models\Setting::get('site_logo'))
                        <img src="{{ asset('storage/' . App\Models\Setting::get('site_logo')) }}" alt="Logo" style="height: {{ App\Models\Setting::get('site_logo_height', '45') }}px;">
                    @else
                        <div class="logo-fallback-icon">हि</div>
                        <h1 class="logo-fallback-text">हिन्दुस्तान</h1>
                    @endif
                </a>
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
                <a href="/" class="pill-item active">होम</a>
                @foreach(\App\Models\Category::whereNull('parent_id')->get() as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="pill-item">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- Breaking News Ticker --}}
    @php
        $breakingNews = \App\Models\Article::where('is_breaking', true)
            ->where('status', 'published')
            ->latest()
            ->get();
    @endphp

    @if($breakingNews->count() > 0)
        <div class="breaking-news-ticker">
            <div class="container ticker-wrapper">
                <div class="ticker-label">BREAKING</div>
                <div class="ticker-content">
                    <div class="ticker-scroll">
                        @foreach($breakingNews as $news)
                            <a href="{{ route('article.show', $news->slug) }}" class="ticker-item">
                                <span class="ticker-bullet">•</span>
                                {{ $news->title }}
                            </a>
                        @endforeach
                        {{-- Duplicate for seamless loop --}}
                        @foreach($breakingNews as $news)
                            <a href="{{ route('article.show', $news->slug) }}" class="ticker-item">
                                <span class="ticker-bullet">•</span>
                                {{ $news->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @php $topAd = \App\Helpers\AdHelper::getAd('top_banner', isset($currentCategory) ? $currentCategory->id : null); @endphp
    @if($topAd)
        <div class="container" style="margin-top: 1rem; text-align: center;">
            <div style="font-size: 0.65rem; color: #94a3b8; margin-bottom: 0.2rem; text-transform: uppercase;">Advertisement</div>
            {!! \App\Helpers\AdHelper::render($topAd) !!}
        </div>
    @endif

    <main class="container main-content-wrapper">
        
        {{-- Topic Chip Scroller --}}
        <div class="topic-scroller">
            <div class="trending-icon">⚡</div>
            @foreach(\App\Models\Tag::take(10)->get() as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}" class="topic-chip">{{ $tag->name }}</a>
            @endforeach
        </div>

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

                {{-- Horizontal Feed Item --}}
                <div class="article-feed">
                    @foreach($articles->slice(5, 5) as $article)
                        <a href="{{ route('article.show', $article->slug) }}" class="feed-item-horizontal">
                            <div class="feed-item-thumb">
                                @if($article->media_id && $article->media)
                                    <img src="{{ $article->media->url }}">
                                @elseif($article->image_path)
                                    <img src="{{ asset('storage/' . $article->image_path) }}">
                                @endif
                            </div>
                            <div class="feed-item-info">
                                <h3>{{ $article->title }}</h3>
                                <div class="feed-item-meta">{{ $article->published_at ? $article->published_at->format('M d, Y h:i A') : $article->created_at->format('M d, Y h:i A') }} IST</div>
                            </div>
                        </a>
                    @endforeach

                    @php 
                        $feedAd = \App\Helpers\AdHelper::getAd('in_feed', isset($currentCategory) ? $currentCategory->id : null); 
                    @endphp
                    @if($feedAd)
                        <div style="margin: 2rem 0;">
                            {!! \App\Helpers\AdHelper::render($feedAd) !!}
                        </div>
                    @endif
                </div>
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
</body>
</html>
