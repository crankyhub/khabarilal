<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Media;
use Illuminate\Support\Str;


class ArticleController extends Controller
{
    const MODERATION_PENDING = 'pending';
    const MODERATION_APPROVED = 'approved';
    const MODERATION_REJECTED = 'rejected';

    public function approve(Article $article)
    {
        $article->update(['moderation_status' => self::MODERATION_APPROVED]);
        return back()->with('success', 'Article approved successfully.');
    }

    public function reject(Request $request, Article $article)
    {
        $article->update([
            'moderation_status' => self::MODERATION_REJECTED,
            'rejection_reason' => $request->input('reason')
        ]);
        return back()->with('success', 'Article rejected.');
    }

    public function index()
    {
        $query = Article::with(['category', 'user']);

        if (!auth()->user()->canApproveArticles()) {
            $query->where('user_id', auth()->id());
        }

        $articles = $query->latest()->paginate(15);
        return view('admin.articles.index', compact('articles'));
    }


    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'summary' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'media_id' => 'nullable|exists:media,id',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = $validated['published_at'] ?? now();
        } else {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = $path;
        }

        // Unique slug check
        $count = Article::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        $article = Article::create($validated);

        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('gallery', 'public');
                $media = Media::create([
                    'filename' => $image->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                ]);
                $article->gallery()->attach($media->id);
            }
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        if (!auth()->user()->canApproveArticles() && $article->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this article.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }


    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'summary' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'media_id' => 'nullable|exists:media,id',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_gallery' => 'nullable|array',
            'remove_gallery.*' => 'exists:media,id',
        ]);

        if ($validated['title'] !== $article->title) {
            $validated['slug'] = Str::slug($validated['title']);
            $count = Article::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $article->id)->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($article->image_path);
            }
            $path = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = $path;
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = $validated['published_at'] ?? ($article->published_at ?? now());
        } else {
            $validated['published_at'] = null;
        }

        if (!auth()->user()->canApproveArticles() && $article->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this article.');
        }

        $article->update($validated);
        \Illuminate\Support\Facades\Cache::forget("article_{$article->slug}");

        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        } else {
            $article->tags()->detach();
        }

        if ($request->has('remove_gallery')) {
            $article->gallery()->detach($request->remove_gallery);
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('gallery', 'public');
                $media = Media::create([
                    'filename' => $image->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                ]);
                $article->gallery()->attach($media->id);
            }
        }

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }


    public function destroy(Article $article)
    {
        if (!auth()->user()->canApproveArticles() && $article->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }

}
