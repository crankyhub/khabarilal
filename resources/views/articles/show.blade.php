<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $article->title }} - {{ App\Models\Setting::get('site_name', 'Khabar-i-Lal') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
            background: var(--brand-yellow);
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
            font-size: clamp(1.75rem, 5vw, 3.5rem);
            line-height: 1.2;
            font-weight: 800;
            color: var(--brand-black);
            margin: 1.5rem 0;
        }

        .article-layout-main {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 4rem;
            margin-top: 2rem;
        }

        @media (max-width: 1024px) {
            .article-layout-main { grid-template-columns: 1fr; gap: 3rem; }
            .article-title { font-size: 2rem; }
        }

        .util-link-back {
            text-decoration: none;
            color: #444;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <header class="article-header-brand">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="/" class="logo-link">
                @if(App\Models\Setting::get('site_logo'))
                    <img src="{{ asset('storage/' . App\Models\Setting::get('site_logo')) }}" alt="Logo" style="height: {{ App\Models\Setting::get('site_logo_height', '40') }}px;">
                @else
                    <div style="background: var(--brand-red); color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 900;">हि</div>
                @endif
            </a>
            <a href="/" class="util-link-back">&larr; Back to Home</a>
        </div>
    </header>

    <article class="container" style="margin-top: 2rem; margin-bottom: 5rem;">
        <div class="article-meta-section">
            <span style="color: var(--brand-red); font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                {{ $article->category->name }}
            </span>
            <h1 class="article-title">{{ $article->title }}</h1>
            
            <div style="display: flex; align-items: center; gap: 1rem; border-top: 1px solid #eee; padding-top: 1.5rem; margin-top: 1.5rem;">
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

        <div class="article-layout-main">
            <div class="article-body">
                <div class="article-content">
                    {!! $article->body !!}
                </div>

                @if($article->tags->count() > 0)
                    <div style="margin-top: 3rem; display: flex; flex-wrap: wrap; gap: 0.75rem;">
                        @foreach($article->tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" style="text-decoration: none; background: #fef2f2; color: var(--brand-red); padding: 0.5rem 1rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 700; border: 1px solid #fee2e2;">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($article->gallery->count() > 0)
                    <div style="margin-top: 4rem;">
                        <h3 style="font-size: 1.5rem; margin-bottom: 2rem; border-left: 5px solid var(--brand-red); padding-left: 1rem;">Photo Gallery</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                            @foreach($article->gallery as $media)
                                <div class="gallery-item" style="border-radius: 12px; overflow: hidden; height: 200px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: transform 0.3s;">
                                    <img src="{{ $media->url }}" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openLightbox(this.src)">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Simple Lightbox --}}
                    <div id="lightbox" onclick="this.style.display='none'" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; cursor: zoom-out; align-items: center; justify-content: center; padding: 2rem;">
                        <img id="lightbox-img" style="max-width: 100%; max-height: 100%; border-radius: 8px; box-shadow: 0 0 50px rgba(0,0,0,0.5);">
                    </div>

                    <script>
                        function openLightbox(src) {
                            const lb = document.getElementById('lightbox');
                            document.getElementById('lightbox-img').src = src;
                            lb.style.display = 'flex';
                        }
                    </script>
                @endif
            </div>

            <aside class="sidebar-column">
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
        </div>
        <div style="text-align: center; margin-top: 3rem; font-size: 0.8rem; color: #999;">
            &copy; 2026 Khabar-i-Lal News Portel. All rights reserved.
        </div>
    </footer>
</body>
</html>
