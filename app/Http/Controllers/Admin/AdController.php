<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\AdPlacement;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::with(['category', 'article', 'placements'])->latest()->paginate(15);
        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        $categories = Category::all();
        $articles = Article::latest()->take(50)->get();
        return view('admin.ads.create', compact('categories', 'articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'article_id' => 'nullable|exists:articles,id',
            'limit_impressions' => 'required|integer|min:0',
            'limit_clicks' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_budget' => 'required|numeric|min:0',
            'cost_per_impression' => 'required|numeric|min:0',
            'cost_per_click' => 'required|numeric|min:0',
            'placements' => 'required|array|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $adData = $request->only([
                'title', 'category_id', 'article_id', 'limit_impressions', 
                'limit_clicks', 'start_date', 'end_date', 'total_budget',
                'cost_per_impression', 'cost_per_click'
            ]);
            $adData['is_active'] = $request->has('is_active');
            $adData['remaining_budget'] = $request->total_budget;
            $adData['status'] = 'active';

            $ad = Ad::create($adData);

            foreach ($request->placements as $pos => $data) {
                if (!isset($data['active'])) continue;

                $placement = new AdPlacement();
                $placement->ad_id = $ad->id;
                $placement->position = $pos;
                $placement->type = $data['type'];
                $placement->link_url = $data['link_url'] ?? null;

                if ($data['type'] === 'image' && isset($data['image'])) {
                    $path = $data['image']->store('ads', 'public');
                    $placement->image_path = $path;
                    $placement->content = asset('storage/' . $path);
                } else {
                    $placement->content = $data['content'] ?? '';
                }

                $placement->save();
            }

            return redirect()->route('admin.ads.index')->with('success', 'Advertisement created successfully.');
        });
    }

    public function edit(Ad $ad)
    {
        $categories = Category::all();
        $articles = Article::latest()->take(50)->get();
        $ad->load('placements');
        $placements = $ad->placements->keyBy('position');
        return view('admin.ads.edit', compact('ad', 'categories', 'articles', 'placements'));
    }

    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'article_id' => 'nullable|exists:articles,id',
            'limit_impressions' => 'required|integer|min:0',
            'limit_clicks' => 'required|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_budget' => 'required|numeric|min:0',
            'cost_per_impression' => 'required|numeric|min:0',
            'cost_per_click' => 'required|numeric|min:0',
            'status' => 'required|in:active,paused,exhausted,expired',
            'placements' => 'required|array|min:1',
        ]);

        return DB::transaction(function () use ($request, $ad) {
            $adData = $request->only([
                'title', 'category_id', 'article_id', 'limit_impressions', 
                'limit_clicks', 'start_date', 'end_date', 'total_budget',
                'cost_per_impression', 'cost_per_click', 'status'
            ]);
            $adData['is_active'] = $request->has('is_active');

            // Budget adjustment
            if ($request->total_budget != $ad->total_budget) {
                $diff = $request->total_budget - $ad->total_budget;
                $adData['remaining_budget'] = $ad->remaining_budget + $diff;
            }

            $ad->update($adData);

            // Sync Placements
            $currentPositions = $ad->placements->pluck('position')->toArray();
            $newPositions = array_keys(array_filter($request->placements, fn($p) => isset($p['active'])));

            // Delete removed placements
            foreach ($ad->placements as $placement) {
                if (!in_array($placement->position, $newPositions)) {
                    if ($placement->image_path) {
                        Storage::disk('public')->delete($placement->image_path);
                    }
                    $placement->delete();
                }
            }

            // Update or Create placements
            foreach ($request->placements as $pos => $data) {
                if (!isset($data['active'])) continue;

                $placement = AdPlacement::where('ad_id', $ad->id)->where('position', $pos)->first() ?: new AdPlacement();
                $placement->ad_id = $ad->id;
                $placement->position = $pos;
                $placement->type = $data['type'];
                $placement->link_url = $data['link_url'] ?? null;

                if ($data['type'] === 'image' && isset($data['image'])) {
                    if ($placement->image_path) {
                        Storage::disk('public')->delete($placement->image_path);
                    }
                    $path = $data['image']->store('ads', 'public');
                    $placement->image_path = $path;
                    $placement->content = asset('storage/' . $path);
                } elseif ($data['type'] === 'script') {
                    $placement->content = $data['content'] ?? '';
                    $placement->image_path = null;
                }

                $placement->save();
            }

            return redirect()->route('admin.ads.index')->with('success', 'Advertisement updated successfully.');
        });
    }

    public function destroy(Ad $ad)
    {
        foreach ($ad->placements as $placement) {
            if ($placement->image_path) {
                Storage::disk('public')->delete($placement->image_path);
            }
        }
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement removed.');
    }
}
