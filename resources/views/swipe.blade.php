<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Khabar-i-Lal Swipe</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background: #000;
        }
        .swipe-container {
            height: 100vh;
            overflow-y: scroll;
            scroll-snap-type: y mandatory;
            -webkit-overflow-scrolling: touch;
        }
        .article-card {
            height: 100vh;
            scroll-snap-align: start;
            scroll-snap-stop: always;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
            box-sizing: border-box;
            background-size: cover;
            background-position: center;
        }
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(0deg, rgba(15,23,42,1) 0%, rgba(15,23,42,0.8) 40%, rgba(15,23,42,0.2) 100%);
            z-index: 1;
        }
        .card-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }
        .card-title {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }
        .card-summary {
            font-size: 1rem;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 2rem;
        }
        .card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 1.5rem;
        }
        .read-more {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .category-tag {
            background: var(--accent);
            padding: 0.2rem 0.6rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .close-swipe {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 10;
            background: rgba(15,23,42,0.8);
            border: 1px solid var(--border);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <a href="/" class="close-swipe">&times;</a>

    <div class="swipe-container" id="swipe-container">
        @foreach($articles as $article)
        <div class="article-card unread-news" data-id="{{ $article->id }}" style="background-image: url('{{ $article->image_path ? asset('storage/' . $article->image_path) : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070' }}');">
            <div class="card-overlay"></div>
            <div class="card-content">
                <span class="category-tag">{{ $article->category->name }}</span>
                <h2 class="card-title">{{ $article->title }}</h2>
                <div class="card-summary">
                    {{ $article->summary ?? Str::limit(strip_tags($article->body), 180) }}
                </div>
                <div class="card-meta">
                    <div style="font-size: 0.8rem; color: #94a3b8;">
                        By <strong>{{ $article->user->name }}</strong> • {{ $article->published_at->diffForHumans() }}
                    </div>
                    <a href="{{ route('article.show', $article->slug) }}" class="read-more">READ MORE &rarr;</a>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Completion Slide --}}
        <div class="article-card completion-slide" style="background: #0f172a; display: none; justify-content: center; align-items: center; text-align: center;">
            <div class="card-overlay" style="background: radial-gradient(circle, rgba(30,41,59,1) 0%, rgba(15,23,42,1) 100%);"></div>
            <div class="card-content">
                <div style="font-size: 4rem; margin-bottom: 1.5rem;">🎉</div>
                <h2 class="card-title">You're all caught up!</h2>
                <p class="card-summary">You have read all news stories. You can visit again later for the latest updates.</p>
                <div style="margin-top: 2rem;">
                    <a href="/" class="btn-back-home" style="text-decoration: none; background: var(--accent); color: white; padding: 0.8rem 2rem; border-radius: 3rem; font-weight: 800; display: inline-block;">BACK TO HOME</a>
                </div>
            </div>
        </div>

        @if($articles->isEmpty())
        <div class="article-card" style="background: #0f172a; justify-content: center; align-items: center; text-align: center;">
             <div class="card-content">
                <h2 class="card-title">Check back later!</h2>
                <p class="card-summary">No stories available to swipe right now.</p>
                <a href="/" style="text-decoration: none; background: var(--accent); color: white; padding: 0.8rem 2rem; border-radius: 3rem; font-weight: 800; display: inline-block;">Back to Website</a>
             </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('swipe-container');
            const completionSlide = document.querySelector('.completion-slide');
            const STORAGE_KEY = 'khabarilal_read_articles';
            
            // Get read IDs from localStorage
            let readIds = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
            
            // Filter articles on load
            const articles = document.querySelectorAll('.unread-news');
            let visibleCount = 0;
            
            articles.forEach(article => {
                const id = article.getAttribute('data-id');
                if (readIds.includes(id)) {
                    article.remove();
                } else {
                    visibleCount++;
                }
            });
            
            // Always show completion slide at the end
            completionSlide.style.display = 'flex';
            
            // If no unread news, show completion slide immediately
            if (visibleCount === 0 && articles.length > 0) {
                // Already handled by completion slide being flex
            }

            // Intersection Observer to mark as read
            const observerOptions = {
                root: container,
                threshold: 0.7 // Mark as read when 70% visible
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.target.classList.contains('unread-news')) {
                        const id = entry.target.getAttribute('data-id');
                        if (!readIds.includes(id)) {
                            readIds.push(id);
                            // Keep last 200 IDs to prevent storage bloat
                            if (readIds.length > 200) readIds.shift();
                            localStorage.setItem(STORAGE_KEY, JSON.stringify(readIds));
                        }
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.unread-news').forEach(article => {
                observer.observe(article);
            });
        });
    </script>
</body>
</html>
