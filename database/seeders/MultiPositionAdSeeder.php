<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ad;
use App\Models\AdPlacement;
use Illuminate\Support\Facades\DB;

class MultiPositionAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ad_placements')->truncate();
        DB::table('ads')->truncate();

        $ad = Ad::create([
            'title' => 'Global Multi-Ad Test',
            'is_active' => true,
            'status' => 'active',
            'total_budget' => 5000.00,
            'remaining_budget' => 5000.00,
            'limit_impressions' => 0,
            'limit_clicks' => 0,
            'cost_per_impression' => 0.1,
            'cost_per_click' => 1.0,
        ]);

        AdPlacement::create([
            'ad_id' => $ad->id,
            'position' => 'top_banner',
            'type' => 'script',
            'content' => '<div style="background: #ef4444; color: white; padding: 20px; font-weight: bold; text-align: center;">MULTI-POSITION: TOP BANNER (728x90)</div>',
        ]);

        AdPlacement::create([
            'ad_id' => $ad->id,
            'position' => 'sidebar',
            'type' => 'script',
            'content' => '<div style="background: #3b82f6; color: white; padding: 50px 20px; font-weight: bold; text-align: center;">MULTI-POSITION: SIDEBAR WIDGET (300x250)</div>',
        ]);

        AdPlacement::create([
            'ad_id' => $ad->id,
            'position' => 'in_feed',
            'type' => 'script',
            'content' => '<div style="background: #10b981; color: white; padding: 30px; font-weight: bold; text-align: center; border-radius: 12px; border: 4px dashed #fff;">MULTI-POSITION: IN-FEED AD (Native Width)</div>',
        ]);
        
        AdPlacement::create([
            'ad_id' => $ad->id,
            'position' => 'popup',
            'type' => 'script',
            'content' => '<div style="background: #ffffff; border: 15px solid #f9c80e; padding: 60px; text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,0.5);">
                <h2 style="color: #ef4444; font-size: 2.5rem; margin-bottom: 20px;">MULTI-POSITION POPUP</h2>
                <p style="font-size: 1.2rem; color: #333;">This ad campaign is running in 4 different positions simultaneously.</p>
                <button style="margin-top: 30px; padding: 15px 40px; background: #f9c80e; border: none; font-weight: bold; font-size: 1.1rem; border-radius: 50px; cursor: pointer;">CHECK IT OUT</button>
            </div>',
        ]);
    }
}
