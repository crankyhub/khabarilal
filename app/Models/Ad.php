<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ad extends Model
{
    protected $fillable = [
        'title', 'is_active', 'category_id', 'article_id', 
        'limit_impressions', 'current_impressions', 
        'limit_clicks', 'current_clicks',
        'start_date', 'end_date', 'total_budget', 'remaining_budget',
        'cost_per_impression', 'cost_per_click', 'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function placements()
    {
        return $this->hasMany(AdPlacement::class);
    }

    /**
     * Check if the ad is eligible to be displayed.
     */
    public function isValid()
    {
        if (!$this->is_active || $this->status !== 'active') {
            return false;
        }

        $now = Carbon::now();

        // Check date range
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            $this->update(['status' => 'expired']);
            return false;
        }

        // Check impression limits
        if ($this->limit_impressions > 0 && $this->current_impressions >= $this->limit_impressions) {
            $this->update(['status' => 'exhausted']);
            return false;
        }

        // Check click limits
        if ($this->limit_clicks > 0 && $this->current_clicks >= $this->limit_clicks) {
            $this->update(['status' => 'exhausted']);
            return false;
        }

        // Check budget
        if ($this->total_budget > 0 && $this->remaining_budget <= 0) {
            $this->update(['status' => 'exhausted']);
            return false;
        }

        return true;
    }

    /**
     * Track an impression and deduct cost from remaining budget.
     */
    public function trackImpression()
    {
        $this->increment('current_impressions');
        
        if ($this->cost_per_impression > 0) {
            $this->decrement('remaining_budget', $this->cost_per_impression);
        }

        $this->checkExhaustion();
    }

    /**
     * Track a click and deduct cost from remaining budget.
     */
    public function trackClick()
    {
        $this->increment('current_clicks');

        if ($this->cost_per_click > 0) {
            $this->decrement('remaining_budget', $this->cost_per_click);
        }

        $this->checkExhaustion();
    }

    /**
     * Internal check to see if ad should be marked as exhausted.
     */
    protected function checkExhaustion()
    {
        $this->refresh();

        if ($this->limit_impressions > 0 && $this->current_impressions >= $this->limit_impressions) {
            $this->update(['status' => 'exhausted']);
        }

        if ($this->limit_clicks > 0 && $this->current_clicks >= $this->limit_clicks) {
            $this->update(['status' => 'exhausted']);
        }

        if ($this->total_budget > 0 && $this->remaining_budget <= 0) {
            $this->update(['status' => 'exhausted']);
        }
    }
}
