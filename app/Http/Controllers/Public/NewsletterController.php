<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $exists = DB::table('newsletter_subscribers')->where('email', $validated['email'])->first();

        if ($exists) {
            return back()->with('success', 'You are already subscribed to our newsletter!');
        }

        DB::table('newsletter_subscribers')->insert([
            'email' => $validated['email'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Thank you for subscribing to Khabar-i-Lal!');
    }
}
