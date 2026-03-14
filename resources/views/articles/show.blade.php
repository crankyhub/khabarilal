<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $article->title }} - {{ App\Models\Setting::get('site_name', 'Khabar-i-Lal') }}</title>

    {{-- Social Sharing Meta Tags (WhatsApp/Facebook/Twitter) --}}
    <meta property="og:title" content="{{ $article->meta_title ?: $article->title }}">
    <meta property="og:description" content="{{ $article->meta_description ?: Str::limit(strip_tags($article->summary ?: $article->body), 160) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    @if($article->media_id && $article->media)
        <meta property="og:image" content="{{ $article->media->url }}">
    @elseif($article->image_path)
        <meta property="og:image" content="{{ asset('storage/' . $article->image_path) }}">
    @endif
    <meta property="og:site_name" content="{{ App\Models\Setting::get('site_name', 'Khabar-i-Lal') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->meta_title ?: $article->title }}">
    <meta name="twitter:description" content="{{ $article->meta_description ?: Str::limit(strip_tags($article->summary ?: $article->body), 160) }}">
    @if($article->media_id && $article->media)
        <meta name="twitter:image" content="{{ $article->media->url }}">
    @elseif($article->image_path)
        <meta name="twitter:image" content="{{ asset('storage/' . $article->image_path) }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="icon" type="image/png" href="{{ App\Models\Setting::get('site_favicon') ? asset('storage/' . App\Models\Setting::get('site_favicon')) : '/favicon.png' }}">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    <script src="{{ asset('js/push-client.js') }}" defer></script>
    <style>
        :root {
            --article-max-width: 1100px;
            --reading-width: 800px;
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

        body {
            background-color: #ffffff;
            color: var(--brand-black);
        }

        .article-content {
            font-size: 1.25rem;
            line-height: 1.8;
            color: #1a1a1a; /* Hard-coded dark for guaranteed visibility */
            font-family: 'Inter', sans-serif;
        }
        .article-content p { margin-bottom: 2rem; }
        .article-content img {
            max-width: 100%;
            border-radius: 12px;
            margin: 2rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .article-header-brand {
            background: var(--header-bg);
            border-bottom: 3px solid var(--brand-red);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .article-meta-section {
            padding: 2rem 0;
        }

        .article-title {
            font-size: clamp(1rem, 2.5vw, 1.75rem);
            line-height: 1.2;
            font-weight: 800;
            color: var(--brand-black);
            margin: 1rem 0;
        }

        .article-layout-main {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 4rem;
            margin-top: 2rem;
        }

        @media (max-width: 1024px) {
            .article-layout-main { grid-template-columns: 1fr; gap: 3rem; }
            .article-title { font-size: 1.25rem; }
        }

        .util-link-back {
            text-decoration: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        /* Gallery Styles */
        .gallery-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.9);
            border: 1px solid #ddd;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--brand-black);
        }
        .gallery-nav-btn:hover {
            background: var(--brand-red);
            color: white;
            border-color: var(--brand-red);
            transform: translateY(-50%) scale(1.1);
        }
        .gallery-nav-btn.left { left: -22px; }
        .gallery-nav-btn.right { right: -22px; }
        
        #gallery-container::-webkit-scrollbar { display: none; }
        
        @media (max-width: 768px) {
            .gallery-nav-btn { display: none; }
        }

        /* Lightbox Zoom Styles */
        #lightbox {
            overflow: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.3) transparent;
        }
        #lightbox::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        #lightbox::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }
        #lightbox-img {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: zoom-in;
            user-select: none;
        }
        #lightbox-img.zoomed {
            max-width: none !important;
            max-height: none !important;
            cursor: zoom-out;
            display: block;
            margin: 5rem auto;
        }

        /* Share Button Styles */
        .share-section {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        .share-title {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: #444;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .share-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .share-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            color: white;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .share-btn:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .share-btn svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }
        .share-whatsapp { background: #25D366; }
        .share-facebook { background: #1877F2; }
        .share-x { background: #000000; }
        .share-telegram { background: #0088cc; }
        .share-email { background: #ea4335; }
        .share-copy { background: #64748b; cursor: pointer; border: none; }
        
        .copy-toast {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: none;
            z-index: 10000;
        }
    </style>
</head>
<body>
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
                    <a href="{{ route('category.show', $cat->slug) }}" class="pill-item {{ (isset($article) && $article->category_id == $cat->id) ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </nav>
    @include('partials.breaking-news')

    @php $topAd = \App\Helpers\AdHelper::getAd('top_banner', $article->category_id, $article->id); @endphp
    @if($topAd)
        <div class="container" style="margin-top: 2rem; text-align: center;">
             <div style="font-size: 0.65rem; color: #94a3b8; margin-bottom: 0.2rem; text-transform: uppercase;">Advertisement</div>
            {!! \App\Helpers\AdHelper::render($topAd) !!}
        </div>
    @endif

    <article class="container" style="margin-top: 1rem; margin-bottom: 5rem;">
        <div class="article-layout-main">
            <div class="article-body">
                <div class="article-meta-section" style="padding-top: 0;">
                    <span style="color: var(--brand-red); font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                        {{ $article->category->name ?? 'News' }}
                    </span>
                    <h1 class="article-title" style="margin-top: 0.5rem;">{{ $article->title }}</h1>
                    
                    <div style="display: flex; align-items: center; gap: 1rem; border-top: 1px solid #eee; padding-top: 1.5rem; margin-top: 1.5rem; margin-bottom: 2rem;">
                        <div style="width: 48px; height: 48px; background: #fff; border-radius: 50%; overflow: hidden; border: 2px solid var(--brand-yellow); display: flex; align-items: center; justify-content: center;">
                            @php $reporter = \App\Models\Reporter::where('user_id', $article->user_id)->first(); @endphp
                            @if($reporter && $reporter->photo_path)
                                <img src="{{ asset('storage/' . $reporter->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @elseif(App\Models\Setting::get('site_logo'))
                                <img src="{{ asset('storage/' . App\Models\Setting::get('site_logo')) }}" style="width: 80%; height: 80%; object-fit: contain;">
                            @else
                                <div style="font-weight: 900; color: var(--brand-red);">हि</div>
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 1.1rem;">
                                @if($reporter)
                                    <a href="{{ route('reporter.show', $reporter->id) }}" style="color: inherit; text-decoration: none;">{{ $article->user->name }}</a>
                                @else
                                    {{ $article->user->name }}
                                @endif
                            </div>
                            <div style="font-size: 0.85rem; color: #666;">Published: {{ $article->published_at->format('M d, Y • h:i A') }}</div>
                        </div>
                    </div>
                </div>

                @if($article->media_id && $article->media)
                    <img src="{{ $article->media->url }}" style="width: 100%; border-radius: 12px; margin-bottom: 2.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                @elseif($article->image_path)
                    <img src="{{ asset('storage/' . $article->image_path) }}" style="width: 100%; border-radius: 12px; margin-bottom: 2.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                @endif
                <div class="article-content">
                    {!! $article->body !!}
                </div>

                @php 
                    $bottomAd = \App\Helpers\AdHelper::getAd('article_bottom', $article->category_id, $article->id); 
                @endphp
                @if($bottomAd)
                    <div style="margin-top: 3rem; padding: 2rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                        <div style="font-size: 0.65rem; color: #94a3b8; margin-bottom: 1rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Sponsor Advertisment</div>
                        {!! \App\Helpers\AdHelper::render($bottomAd) !!}
                    </div>
                @endif


                @if($article->tags->count() > 0)
                    <div style="margin-top: 3rem; margin-bottom: -2rem; display: flex; flex-wrap: wrap; gap: 0.75rem;">
                        @foreach($article->tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" style="text-decoration: none; background: #fef2f2; color: var(--brand-red); padding: 0.5rem 1rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 700; border: 1px solid #fee2e2;">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="share-section">
                    <div class="share-title">
                        <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; fill: var(--brand-red);"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                        Share this Story
                    </div>
                    <div class="share-buttons">
                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . url()->current()) }}" target="_blank" class="share-btn share-whatsapp" title="Share on WhatsApp">
                            <svg viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217s.231.006.332.009c.109.004.258-.041.405.314.159.386.541 1.321.588 1.417.047.096.078.208.014.335-.064.127-.096.208-.191.318-.096.11-.2.243-.286.327-.101.098-.207.205-.089.408.118.203.526.868 1.129 1.405.777.692 1.432.907 1.635 1.009.202.102.321.085.441-.054.12-.139.516-.599.654-.803.138-.204.276-.171.465-.101.189.07 1.201.567 1.408.671.207.104.345.154.394.238.049.084.049.491-.095.896z"/></svg>
                        </a>
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn share-facebook" title="Share on Facebook">
                            <svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        {{-- X --}}
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" class="share-btn share-x" title="Share on X">
                            <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        {{-- Telegram --}}
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" class="share-btn share-telegram" title="Share on Telegram">
                            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.13-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.37.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .33z"/></svg>
                        </a>
                        {{-- Email --}}
                        <a href="mailto:?subject={{ $article->title }}&body={{ url()->current() }}" class="share-btn share-email" title="Share via Email">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </a>
                        {{-- Copy Link --}}
                        <button onclick="copyToClipboard()" class="share-btn share-copy" title="Copy Link">
                            <svg viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                        </button>
                    </div>
                </div>

                <div id="copy-toast" class="copy-toast">Link Copied to Clipboard!</div>

                {{-- Comments Section --}}
                <div class="comments-section" id="comments">
                    <h3 class="comments-title">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="var(--brand-red)" style="vertical-align: middle; margin-right: 0.5rem;"><path d="M21 15c0 1.1-.9 2-2 2H7l-4 4V5c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v10z"/></svg>
                        Comments ({{ $article->comments()->where('is_approved', true)->count() }})
                    </h3>

                    @if(session('success'))
                        <div style="background: #f0fdf4; color: #166534; padding: 1rem; border-radius: 8px; border: 1px solid #bbf7d0; margin-bottom: 2rem; font-weight: 600;">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Comment Form --}}
                    <div class="comment-form-card">
                        <form action="{{ route('comments.store', $article->id) }}" method="POST">
                            @csrf
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Name <span style="color: var(--brand-red);">*</span></label>
                                    <input type="text" name="user_name" class="form-control" required placeholder="Your full name">
                                    @error('user_name') <small style="color: var(--brand-red);">{{ $message }}</small> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email ID</label>
                                    <input type="email" name="email" class="form-control" placeholder="Optional: your@email.com">
                                    @error('email') <small style="color: var(--brand-red);">{{ $message }}</small> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Phone No.</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Optional: mobile number">
                                    @error('phone') <small style="color: var(--brand-red);">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <div class="form-group">
                                    <label class="form-label">Your Comment <span style="color: var(--brand-red);">*</span></label>
                                    <textarea name="content" class="form-control" rows="4" required placeholder="Type your comment here..."></textarea>
                                    @error('content') <small style="color: var(--brand-red);">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div style="margin-bottom: 1.5rem;">
                                <div class="form-group">
                                    <label class="form-label">Human Verification <span style="color: var(--brand-red);">*</span></label>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div style="background: #e2e8f0; padding: 0.75rem 1rem; border-radius: 0.5rem; font-weight: 800; color: var(--brand-black); font-size: 1.1rem; border: 1px solid #cbd5e1;">
                                            {{ $num1 }} + {{ $num2 }} = ?
                                        </div>
                                        <input type="number" name="captcha" class="form-control" style="max-width: 120px;" required placeholder="Result">
                                    </div>
                                    @error('captcha') <small style="color: var(--brand-red); display: block; margin-top: 0.5rem;">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; border-radius: 3rem; font-weight: 700;">Submit Comment</button>
                        </form>
                    </div>

                    {{-- Approved Comments List --}}
                    <div class="comments-list">
                        @php $approvedComments = $article->comments()->where('is_approved', true)->latest()->get(); @endphp
                        @forelse($approvedComments as $comment)
                            <div class="comment-item">
                                <div class="comment-avatar">
                                    {{ substr($comment->user_name, 0, 1) }}
                                </div>
                                <div class="comment-body">
                                    <div class="comment-header">
                                        <span class="comment-author">{{ $comment->user_name }}</span>
                                        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="comment-text">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #94a3b8; padding: 2rem 0; font-style: italic;">
                                No comments yet. Be the first to share your thoughts!
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($article->gallery->count() > 0)
                    <div style="margin-top: 4rem;">
                        <h3 style="font-size: 1.5rem; margin-bottom: 2rem; border-left: 5px solid var(--brand-red); padding-left: 1rem;">Photo Gallery</h3>
                        
                        <div style="position: relative;">
                            <button class="gallery-nav-btn left" onclick="scrollGallery(-1)">←</button>
                            
                            <div id="gallery-container" style="display: flex; overflow-x: auto; scroll-behavior: smooth; gap: 1.5rem; scrollbar-width: none; -ms-overflow-style: none; padding-bottom: 0.5rem;">
                                @foreach($article->gallery as $media)
                                    <div class="gallery-item" style="flex: 0 0 300px; border-radius: 12px; overflow: hidden; height: 200px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s; scroll-snap-align: start;">
                                        <img src="{{ $media->url }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openLightbox(this.src)" loading="lazy">
                                    </div>
                                @endforeach
                            </div>

                            <button class="gallery-nav-btn right" onclick="scrollGallery(1)">→</button>
                        </div>
                    </div>

                    {{-- Simple Lightbox --}}
                    <div id="lightbox" onclick="closeLightbox(event)" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
                        <img id="lightbox-img" onclick="toggleZoom(event)" style="max-width: 100%; max-height: 100%; border-radius: 8px; box-shadow: 0 0 50px rgba(0,0,0,0.5);">
                    </div>

                    <script>
                        function openLightbox(src) {
                            const lb = document.getElementById('lightbox');
                            const img = document.getElementById('lightbox-img');
                            img.src = src;
                            img.classList.remove('zoomed');
                            lb.style.display = 'flex';
                            lb.style.alignItems = 'center';
                            document.body.style.overflow = 'hidden'; // Prevent background scroll
                        }

                        function closeLightbox(event) {
                            if (event.target.id === 'lightbox') {
                                document.getElementById('lightbox').style.display = 'none';
                                document.body.style.overflow = 'auto';
                            }
                        }

                        function toggleZoom(event) {
                            event.stopPropagation();
                            const img = document.getElementById('lightbox-img');
                            const lb = document.getElementById('lightbox');
                            img.classList.toggle('zoomed');
                            
                            if (img.classList.contains('zoomed')) {
                                lb.style.display = 'block';
                                lb.style.textAlign = 'center';
                            } else {
                                lb.style.display = 'flex';
                                lb.style.alignItems = 'center';
                                lb.style.textAlign = 'left';
                            }
                        }

                        function scrollGallery(direction) {
                            const container = document.getElementById('gallery-container');
                            const scrollAmount = direction * 320; // Width of item + gap
                            container.scrollBy({
                                left: scrollAmount,
                                behavior: 'smooth'
                            });
                        }

                        function copyToClipboard() {
                            const dummy = document.createElement('input');
                            const text = window.location.href;

                            document.body.appendChild(dummy);
                            dummy.value = text;
                            dummy.select();
                            document.execCommand('copy');
                            document.body.removeChild(dummy);

                            const toast = document.getElementById('copy-toast');
                            toast.style.display = 'block';
                            setTimeout(() => {
                                toast.style.display = 'none';
                            }, 3000);
                        }
                    </script>
                @endif
            </div>

            <aside class="site-sidebar">
                @php 
                    $sidebarAd = \App\Helpers\AdHelper::getAd('sidebar', $article->category_id, $article->id); 
                @endphp
                @if($sidebarAd)
                    <div style="margin-bottom: 2rem;">
                        {!! \App\Helpers\AdHelper::render($sidebarAd) !!}
                    </div>
                @endif

                <div class="card" style="position: sticky; top: 100px;">
                    <h3 style="font-size: 1.3rem; margin-bottom: 1.5rem; border-left: 5px solid var(--brand-red); padding-left: 1rem;">Trending</h3>
                    @foreach(\App\Models\Article::where('status', 'published')->orderBy('views_count', 'desc')->take(5)->get() as $trend)
                        <a href="{{ route('article.show', $trend->slug) }}" style="display: flex; align-items: center; gap: 1rem; text-decoration: none; margin-bottom: 1.25rem;">
                            <div style="width: 60px; height: 60px; background: #f8fafb; border-radius: 8px; overflow: hidden; flex-shrink: 0;">
                                @if($trend->image_path)
                                    <img src="{{ asset('storage/' . $trend->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            <div style="font-weight: 700; color: var(--brand-black); font-size: 0.95rem; line-height: 1.3;">{{ $trend->title }}</div>
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </article>

    <footer class="main-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                @if(App\Models\Setting::get('site_logo'))
                    <img src="{{ asset('storage/' . App\Models\Setting::get('site_logo')) }}" alt="Logo" style="height: {{ App\Models\Setting::get('site_logo_height', '40') }}px; margin-bottom: 1rem;">
                @else
                    <div class="footer-logo">हिन्दुस्तान</div>
                @endif
                <p>Authentic news, delivered straight to your world.</p>
            </div>
            <div class="footer-contact">
                <h4>संपर्क</h4>
                <p>Email: support@khabarilal.com<br>Phone: +91 120 1234567</p>
            </div>
            <div class="footer-goto-top">
                <button onclick="scrollToTop()" title="Go to Top">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                </button>
            </div>
        </div>
        <div style="text-align: center; margin-top: 3rem; font-size: 0.8rem; color: #999;">
            &copy; 2026 Khabar-i-Lal News Portel. All rights reserved.
        </div>
    </footer>

    {{-- Popup Ad --}}
    @php $popupAd = \App\Helpers\AdHelper::getAd('popup', $article->category_id, $article->id); @endphp
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
            @php
                $categories = \App\Models\Category::whereNull('parent_id')->get();
            @endphp
            @foreach($categories as $cat)
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

            if (toggle) {
                function toggleDrawer() {
                    drawer.classList.toggle('active');
                    overlay.classList.toggle('active');
                    body.classList.toggle('drawer-open');
                }

                toggle.addEventListener('click', toggleDrawer);
                close.addEventListener('click', toggleDrawer);
                overlay.addEventListener('click', toggleDrawer);
            }
        });

        // Inshorts View Logic
        const inshortsArticles = {!! json_encode(\App\Models\Article::where('status', 'published')->latest()->take(20)->get()->map(function($a) {
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
                if(btn) btn.classList.remove('active');
            } else {
                if (container.children.length === 0) {
                    renderInshorts();
                }
                container.classList.add('active');
                body.classList.add('inshorts-active');
                if(btn) btn.classList.add('active');
            }
        }

        function renderInshorts() {
            const container = document.getElementById('inshorts-container');
            container.innerHTML = '<button class="inshorts-close" onclick="toggleInshortView()">✕</button>';
            
            inshortsArticles.forEach(article => {
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
