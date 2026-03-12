@extends('layouts.admin')

@section('header', 'Edit Article: ' . $article->title)

@section('content')
<div class="card">
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div class="main-form-area">
                <div class="form-group">
                    <label class="form-label">Article Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $article->title }}" required style="font-size: 1.25rem; font-weight: 700;">
                    @error('title') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Short Summary (Inshort Mode)</label>

                    <textarea name="summary" id="summary" class="form-control" rows="3">{{ $article->summary }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Article Body</label>
                    <textarea name="body" class="editor" style="min-height: 500px;">{{ $article->body }}</textarea>
                    @error('body') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="sidebar-form-area">
                <div class="card" style="margin-bottom: 1.5rem; background: rgba(15, 23, 42, 0.5);">
                    <h4>Publishing Settings</h4>
                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    <div class="form-group" id="breaking-news-group" style="margin-top: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem; color: #fff;">
                            <input type="checkbox" name="is_breaking" value="1" {{ $article->is_breaking ? 'checked' : '' }}>
                            <span style="font-weight: 600; color: #facc15;">🔥 Mark as Breaking News</span>
                        </label>
                    </div>

                    <div class="form-group" id="publish-date-group" style="{{ $article->status === 'published' ? 'display: block;' : 'display: none;' }}">
                        <label class="form-label">Publication Date</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="{{ $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $article->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="form-label">Tags</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; max-height: 200px; overflow-y: auto; padding: 1rem; background: var(--bg-dark); border-radius: 0.5rem; border: 1px solid var(--border);">
                            @foreach($tags as $tag)
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; color: var(--text-secondary);">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'checked' : '' }}>
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 1rem;">
                        <strong>Last Updated:</strong> {{ $article->updated_at->format('M d, H:i') }}
                    </div>
                </div>

                <div class="card" style="margin-bottom: 1.5rem; background: rgba(15, 23, 42, 0.5);">
                    <h4>SEO Settings</h4>
                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ $article->meta_title }}" placeholder="Search engine title...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Search engine description...">{{ $article->meta_description }}</textarea>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 1.5rem; background: rgba(15, 23, 42, 0.5);">
                    <h4>Featured Image</h4>
                    @if($article->image_path)
                        <div style="margin-bottom: 1rem; border-radius: 0.5rem; overflow: hidden; height: 120px;">
                            <img src="{{ asset('storage/' . $article->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endif
                    <div class="form-group">
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @error('image') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="card" style="background: rgba(15, 23, 42, 0.5); border: 2px dashed rgba(255, 255, 255, 0.1);">
                    <h4>Article Gallery</h4>
                    @if($article->gallery->count() > 0)
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 1rem;">
                            @foreach($article->gallery as $media)
                                <div style="position: relative; height: 60px; border-radius: 4px; overflow: hidden;">
                                    <img src="{{ $media->url }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    <label style="position: absolute; top: 2px; right: 2px; background: rgba(255,0,0,0.7); border-radius: 50%; width: 16px; height: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px;">
                                        <input type="checkbox" name="remove_gallery[]" value="{{ $media->id }}" style="display: none;">
                                        🗑️
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p style="font-size: 0.65rem; color: var(--text-secondary); margin-bottom: 1rem;">Select 🗑️ and update to remove.</p>
                    @endif
                    
                    <div class="form-group">
                        <label class="form-label" style="font-size: 0.8rem;">Add More Images</label>
                        <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                        @error('gallery_images') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">Update Article</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline" style="width: 100%; text-decoration: none; border: 1px solid var(--border); color: var(--text-secondary); text-align: center;">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('scripts')
<script>


    const statusSelect = document.querySelector('select[name="status"]');
    const publishDateGroup = document.getElementById('publish-date-group');

    if (statusSelect && publishDateGroup) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'published') {
                publishDateGroup.style.display = 'block';
            } else {
                publishDateGroup.style.display = 'none';
            }
        });
    }
</script>
@endsection
