<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $articleId)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'content' => 'required|string|min:3',
            'captcha' => ['required', 'integer', function ($attribute, $value, $fail) {
                if ($value != session('comment_captcha')) {
                    $fail('The captcha answer is incorrect.');
                }
            }],
        ]);

        // Clear captcha after successful validation
        session()->forget('comment_captcha');

        Comment::create([
            'article_id' => $articleId,
            'user_name' => $validated['user_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'content' => $validated['content'],
            'is_approved' => false, // Require moderation
        ]);

        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }
}
