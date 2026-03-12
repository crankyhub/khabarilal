<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $mediaItems = Media::latest()->paginate(24);
        return view('admin.media.index', compact('mediaItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('gallery', 'public');
                
                Media::create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'disk' => 'public',
                ]);
            }
        }

        return back()->with('success', 'Media uploaded successfully.');
    }

    public function destroy(Media $medium)
    {
        $medium->delete();
        return back()->with('success', 'Media deleted successfully.');
    }

    public function apiIndex()
    {
        return response()->json(Media::latest()->get());
    }
}
