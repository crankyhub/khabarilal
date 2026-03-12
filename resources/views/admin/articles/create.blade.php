@extends('layouts.admin')

@section('header', 'Write New Article')

@section('content')
<div class="card">
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div class="main-form-area">
                <div class="form-group">
                    <label class="form-label">Article Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter a catchy headline..." required autofocus style="font-size: 1.25rem; font-weight: 700;">
                    @error('title') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                        <label class="form-label">Short Summary (Inshort Mode)</label>

                    <textarea name="summary" id="summary" class="form-control" rows="3" placeholder="Brief 60-word summary for the swipe app..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Article Body</label>
                    <textarea name="body" class="editor" style="min-height: 500px;"></textarea>
                    @error('body') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="sidebar-form-area">
                <div class="card" style="margin-bottom: 1.5rem; background: rgba(15, 23, 42, 0.5);">
                    <h4>Publishing Settings</h4>
                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="draft">Draft</option>
                            <option value="published">Publish Immediately</option>
                        </select>
                    </div>

                    <div class="form-group" id="breaking-news-group" style="margin-top: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem; color: #fff;">
                            <input type="checkbox" name="is_breaking" value="1">
                            <span style="font-weight: 600; color: #facc15;">🔥 Mark as Breaking News</span>
                        </label>
                    </div>

                    <div class="form-group" id="publish-date-group" style="display: none;">
                        <label class="form-label">Publication Date</label>
                        <input type="datetime-local" name="published_at" class="form-control">
                        <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.3rem;">Leave empty to publish immediately upon saving.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control" placeholder="news, sports, चुनाव..." value="{{ old('tags') }}">
                        <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.3rem;">Separate tags with commas.</p>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 1.5rem; background: rgba(15, 23, 42, 0.5);">
                    <h4>SEO & URL Settings</h4>
                    <div class="form-group" style="margin-top: 1rem;">
                        <label class="form-label">Article Slug (URL)</label>
                        <input type="text" name="slug" id="article-slug" class="form-control" placeholder="custom-url-path...">
                        <p style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.3rem;">Leave empty to auto-generate from title.</p>
                        @error('slug') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" placeholder="Search engine title...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3" placeholder="Search engine description..."></textarea>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 1.5rem; background: rgba(15, 23, 42, 0.5);">
                    <h4>Featured Image</h4>
                    <div class="form-group" style="margin-top: 1rem;">
                        <div id="image-preview-container" style="display: none; margin-bottom: 1rem; position: relative;">
                            <img id="image-preview" src="" style="width: 100%; border-radius: 0.5rem; height: 150px; object-fit: cover;">
                            <button type="button" onclick="clearSelectedImage()" style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(0,0,0,0.5); border: none; color: white; border-radius: 50%; width: 24px; height: 24px; cursor: pointer;">×</button>
                        </div>
                        
                        <div id="upload-controls">
                            <input type="file" name="image" id="image-file" class="form-control" accept="image/*">
                            <div style="text-align: center; margin: 1rem 0; color: var(--text-secondary); font-size: 0.8rem;">— OR —</div>
                            <button type="button" onclick="openMediaPicker()" class="btn btn-outline" style="width: 100%;">Choose from Gallery</button>
                        </div>
                        
                        <input type="hidden" name="media_id" id="selected-media-id">
                        
                        @error('image') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="card" style="background: rgba(15, 23, 42, 0.5); border: 2px dashed rgba(255, 255, 255, 0.1);">
                    <h4>Article Gallery</h4>
                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 1rem;">Upload multiple photos related to this article.</p>
                    <div class="form-group">
                        <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/*">
                        @error('gallery_images') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                        @error('gallery_images.*') <span style="color: var(--danger); font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                @include('admin.media.picker')

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">Save Article</button>
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

    // Auto-slug generation
    const titleInput = document.querySelector('input[name="title"]');
    const slugInput = document.getElementById('article-slug');
    let isSlugManual = false;

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!isSlugManual) {
                slugInput.value = this.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^\p{L}\p{N}\p{M}\s-]/gu, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });

        slugInput.addEventListener('input', function() {
            isSlugManual = this.value.length > 0;
        });
    }

    window.addEventListener('mediaSelected', function(e) {
        const { id, url } = e.detail;
        document.getElementById('selected-media-id').value = id;
        document.getElementById('image-preview').src = url;
        document.getElementById('image-preview-container').style.display = 'block';
        document.getElementById('upload-controls').style.display = 'none';
        // Clear file input if picking from gallery
        document.getElementById('image-file').value = '';
    });

    function clearSelectedImage() {
        document.getElementById('selected-media-id').value = '';
        document.getElementById('image-preview-container').style.display = 'none';
        document.getElementById('upload-controls').style.display = 'block';
    }

    document.getElementById('image-file').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            document.getElementById('selected-media-id').value = '';
        }
    });
</script>
@endsection
