@extends('layouts.admin')

@section('header', 'Media Gallery')

@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1.5rem;">Upload New Assets</h3>
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display: flex; gap: 1rem; align-items: center;">
            <input type="file" name="files[]" multiple class="form-control" style="flex: 1;" accept="image/*">
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">Upload</button>
        </div>
        <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem;">Max 5MB per image. WebP, PNG, JPG supported.</p>
    </form>
</div>

<div class="media-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.5rem;">
    @foreach($mediaItems as $item)
    <div class="card" style="padding: 0; overflow: hidden; position: relative; group: hover;">
        <div style="height: 150px; background: #1e293b; display: flex; align-items: center; justify-content: center;">
            <img src="{{ $item->url }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>
        <div style="padding: 0.75rem;">
            <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->filename }}">
                {{ $item->filename }}
            </div>
            <div style="font-size: 0.65rem; color: var(--text-secondary); margin-top: 0.2rem;">
                {{ number_format($item->size / 1024, 1) }} KB
            </div>
        </div>
        <div style="position: absolute; top: 0.5rem; right: 0.5rem;">
            <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Permanently delete this file?');">
                @csrf @method('DELETE')
                <button type="submit" style="background: rgba(239, 68, 68, 0.9); border: none; color: white; border-radius: 0.5rem; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">×</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div style="margin-top: 2rem;">
    {{ $mediaItems->links() }}
</div>
@endsection
