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
        <p class="article-excerpt">{{ $article->summary ? strip_tags($article->summary) : \Str::limit(strip_tags($article->body), 180) }}</p>
        <div class="feed-item-meta">{{ $article->published_at ? $article->published_at->format('M d, Y h:i A') : ($article->created_at ? $article->created_at->format('M d, Y h:i A') : now()->format('M d, Y h:i A')) }} IST</div>
    </div>
</a>
