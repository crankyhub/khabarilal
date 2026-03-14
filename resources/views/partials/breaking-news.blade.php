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
                <div class="ticker-scroll" style="animation-duration: {{ \App\Models\Setting::get('ticker_speed', 15) }}s;">
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
