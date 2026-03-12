<?php

namespace App\Helpers;

use App\Models\Ad;
use App\Models\AdPlacement;
use Illuminate\Support\Facades\Log;

class AdHelper
{
    /**
     * Get a valid ad placement for a specific position and context.
     * 
     * @param string $position The layout position (e.g. 'sidebar', 'top_banner')
     * @param int|null $category_id Optional category ID for contextual ads
     * @param int|null $article_id Optional article ID for article-specific ads
     * @return AdPlacement|null
     */
    public static function getAd($position, $category_id = null, $article_id = null)
    {
        // 1. Try to find an ad placement specifically for this article
        if ($article_id) {
            $placement = AdPlacement::where('position', $position)
                ->whereHas('ad', function($query) use ($article_id) {
                    $query->where('article_id', $article_id)
                        ->where('is_active', true)
                        ->where('status', 'active');
                })
                ->inRandomOrder()
                ->get()
                ->first(function($p) {
                    return $p->ad->isValid();
                });
            
            if ($placement) {
                $placement->ad->trackImpression();
                return $placement;
            }
        }

        // 2. Try to find an ad placement specifically for this category
        if ($category_id) {
            $placement = AdPlacement::where('position', $position)
                ->whereHas('ad', function($query) use ($category_id) {
                    $query->where('category_id', $category_id)
                        ->where('is_active', true)
                        ->where('status', 'active');
                })
                ->inRandomOrder()
                ->get()
                ->first(function($p) {
                    return $p->ad->isValid();
                });

            if ($placement) {
                $placement->ad->trackImpression();
                return $placement;
            }
        }

        // 3. Fallback to universal ads for this position
        $placement = AdPlacement::where('position', $position)
            ->whereHas('ad', function($query) {
                $query->whereNull('category_id')
                    ->whereNull('article_id')
                    ->where('is_active', true)
                    ->where('status', 'active');
            })
            ->inRandomOrder()
            ->get()
            ->first(function($p) {
                return $p->ad->isValid();
            });

        if ($placement) {
            $placement->ad->trackImpression();
            return $placement;
        }

        return null;
    }

    /**
     * Render the ad HTML.
     */
    public static function render($placement)
    {
        if (!$placement) return '';

        if ($placement->type === 'image') {
            $html = '<div class="ad-placement ad-pos-' . $placement->position . '">';
            if ($placement->link_url) {
                $html .= '<a href="' . route('ads.click', $placement->ad_id) . '" target="_blank">';
            }
            $html .= '<img src="' . $placement->content . '" alt="' . ($placement->ad->title ?? 'Advertisment') . '" style="max-width:100%; height:auto;">';
            if ($placement->link_url) {
                $html .= '</a>';
            }
            $html .= '</div>';
            return $html;
        }

        return '<div class="ad-placement ad-pos-' . $placement->position . '">' . $placement->content . '</div>';
    }
}
