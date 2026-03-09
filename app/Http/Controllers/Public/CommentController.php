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
            'content' => 'required|string|min:3'
        ]);

        Comment::create([
            'article_id' => $articleId,
            'user_name' => $validated['user_name'],
            'content' => $validated['content'],
            'is_approved' => false, // Require moderation
        ]);

        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }
}
