<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $reporter->user->name }} - Reporter Profile | Khabar-i-Lal</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .profile-header {
            background: var(--bg-card);
            padding: 4rem 0;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        .profile-photo {
            width: 150px;
            height: 150px;
            background: #334155;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            overflow: hidden;
            border: 4px solid var(--accent);
        }
        .bio {
            max-width: 600px;
            margin: 1.5rem auto;
            color: var(--text-secondary);
            line-height: 1.6;
        }
        .beat-tag {
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent);
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <header style="background: var(--bg-card); border-bottom: 1px solid var(--border); padding: 1rem 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <a href="/" class="brand" style="margin-bottom: 0; text-decoration: none;">Khabar-i-Lal</a>
            <nav>
                <a href="/" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem;">&larr; Back to News</a>
            </nav>
        </div>
    </header>

    <section class="profile-header">
        <div class="container">
            <div class="profile-photo">
                @if($reporter->photo_path)
                    <img src="{{ asset('storage/' . $reporter->photo_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>
            <h1 style="font-size: 2.5rem; letter-spacing: -0.05em; margin-bottom: 0.5rem;">{{ $reporter->user->name }}</h1>
            @if($reporter->beat)
                <span class="beat-tag">{{ $reporter->beat }} Correspondent</span>
            @endif
            <p class="bio">{{ $reporter->bio ?? 'No biography available for this correspondent.' }}</p>
        </div>
    </section>

    <main class="container" style="max-width: 1200px; margin: 4rem auto; padding: 0 1.5rem;">
        <h2 style="margin-bottom: 3rem; font-size: 1.75rem; border-bottom: 2px solid var(--accent); display: inline-block; padding-bottom: 0.5rem;">Latest Stories by {{ $reporter->user->name }}</h2>
        
        <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2.5rem;">
            @foreach($articles as $article)
                <article class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="height: 200px; background: #334155;">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <div style="padding: 1.5rem; flex: 1;">
                        <span style="color: var(--accent); font-size: 0.75rem; font-weight: 700;">{{ $article->category->name }}</span>
                        <h3 style="margin: 0.5rem 0 1rem; line-height: 1.3;">
                            <a href="{{ route('article.show', $article->slug) }}" style="color: var(--text-primary); text-decoration: none;">{{ $article->title }}</a>
                        </h3>
                        <div style="color: var(--text-secondary); font-size: 0.8rem; margin-top: auto;">
                            {{ $article->published_at->format('M d, Y') }}
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 3rem;">
            {{ $articles->links() }}
        </div>
    </main>

    <footer style="padding: 4rem 0; border-top: 1px solid var(--border); text-align: center; color: var(--text-secondary); background: var(--bg-card);">
        <div class="container">
            <div class="brand" style="margin-bottom: 1rem;">Khabar-i-Lal</div>
            <p style="font-size: 0.8rem;">&copy; 2026 Khabar-i-Lal News CMS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
