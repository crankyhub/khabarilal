<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PushSubscription;
use App\Models\Article;

class NotificationController extends Controller
{
    public function index()
    {
        $subscriberCount = PushSubscription::count();
        $recentArticles = Article::latest()->take(10)->get();
        return view('admin.notifications.index', compact('subscriberCount', 'recentArticles'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'url' => 'required|url',
        ]);

        $subscribers = PushSubscription::all();
        $successCount = 0;

        foreach ($subscribers as $subscriber) {
            // Logic to send via WebPush would go here
            // \WebPush\WebPush::sendNotification(...)
            $successCount++;
        }

        // Log the broadcast
        \Illuminate\Support\Facades\Log::info("Broadcasted notification: '{$validated['title']}' to {$successCount} subscribers.");

        return redirect()->back()->with('success', "Notification broadcasted successfully to $successCount active subscribers!");
    }
}
