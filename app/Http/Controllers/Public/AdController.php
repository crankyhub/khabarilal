<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ad;

class AdController extends Controller
{
    /**
     * Track an ad click and redirect to the destination URL.
     */
    public function click(Ad $ad)
    {
        $ad->trackClick();

        if ($ad->link_url) {
            return redirect($ad->link_url);
        }

        return redirect()->back();
    }
}
